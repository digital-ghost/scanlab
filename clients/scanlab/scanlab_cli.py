#!/usr/bin/python
'''
    ++ Python ScanLab Client v0.2b ++
'''
import sys
import os
import time
import base64
import json
import urllib
import urllib2
import itertools
import string
import re
from hashlib import sha1
from config import *
from optparse import OptionParser
import xml.etree.ElementTree as ET

try:
    import pygeoip
except:
    print("!! You need to install pygeoip package !!\nDo: sudo apt-get install -y python-pip && sudo pip install pygeoip")
    sys.exit(-1)

try:
    import sleekxmpp
except:
    print("!! You need to install sleekxmpp package !!\nDo: sudo pip install sleekxmpp")
    sys.exit(-1)

time_begin = time.time()
gi = pygeoip.GeoIP(geoip_file)
auth_hash = sha1(sha1(code).hexdigest() + salt).hexdigest()
cwd = os.getcwd() + "/"

'''
    Monkey patch for TOR to work
'''
if use_tor == True:
    try:
        import socks
    except:
        print("!! You need to install Socks package !!")
        print("Do: sudo apt-get install python-socksipy OR sudo pip install PySocks")
        sys.exit(-1)
    import socket
    socks.setdefaultproxy(socks.PROXY_TYPE_SOCKS5, "127.0.0.1", 9050)
    socket.socket = socks.socksocket
    
    def getaddrinfo(*args):
        return [(socket.AF_INET, socket.SOCK_STREAM, 6, '', (args[0], args[1]))]
    
    socket.getaddrinfo = getaddrinfo

'''
    Return tags found in report
    This function is very basic at that moment
'''
def get_tags(report):
    return_tags = []
    for tag in sl_tags:
        for tag_word in sl_tags[tag]:
            if tag_word in report["raw_xml"] and tag not in return_tags:
                return_tags.append(tag)

    return return_tags

'''
    Get target from API
'''
def get_target():
    params = {
        'code' : auth_hash,
        'user' : user
    }

    data = urllib.urlencode(params)
    req = urllib2.Request(url = api_url+"get_target", data = data)
    try:
        return urllib2.urlopen(req).read()
    except:
        print("!! Can't connect to server")
        exit()

'''
    Send reports to server
'''
def send_reports(reports, user, auth_hash, i):
    reports = base64.b64encode(json.dumps(reports))

    params = {
        'code' : auth_hash,
        'user' : user,
        'reports' : reports
    } 

    data = urllib.urlencode(params)
    req = urllib2.Request(url = api_url+"insert", data = data)

    try:
        content = urllib2.urlopen(req).read()
    except:
        print("!! Can't connect to server")
        exit()

    if content == '1': 
        print('++ packet sent ({0} total)'.format(i))
    elif content == '2':
        print('!! API is not enabled for this user')
        exit()
    elif content == '3':
        print('!! Account limit reached')
        exit()
    elif content == '0':
        print('!! Invalid username/password')
        exit()
    else:
        print(content)

'''
    Check report if it is junk or not
'''
def check_report(report, send_all):
    if send_all is True:
        return True
    if report['report']['status'] != 'up':
        return False
    for port in report['report']['ports']:
        if port['state'] == 'open':
            return True
    return False

'''
    Escape target argument
'''
def escape_target(target):
    p = re.compile('[^a-z0-9.:\-/*]')
    return p.sub('', target)

'''
    Parse and send file
'''
def parse_and_send(file_name):
    try:
        xml = ET.parse(file_name).getroot()
    except:
        print("!! xml file is corrupted")
        exit()

    reports = []
    i = 0
    for host in xml.findall('host'):
        report = {}
        addr = host.find('address').attrib['addr']

        hostname = "" 
        if host.find('hostnames') is not None and host.find('hostnames').find('hostname') is not None:
            hostname = host.find('hostnames').find('hostname').attrib['name']
        ports_array = []
        if host.find('ports') is not None:
            for port in host.find('ports').findall('port'):
                p = {}
                p['portid'] = port.attrib['portid']
                p['protocol'] = port.attrib['protocol']
                if len(port.findall('state')):
                    p['state'] = port.find('state').attrib['state']
                if len(port.findall('service')):
                    service = port.find('service')
                    p['service'] = { 'name' : service.attrib['name']}

                ports_array.append(p)

        country_code = gi.country_code_by_addr(addr)
        # It must be somwhere in Antarctica
        if country_code == "":
            country_code = "AQ"

        report['report'] = {
            'status' : host.find('status').attrib['state'],
            'hostname' : hostname,
            'address' : addr,
            'geoip' : { 'country': country_code },
            'ports' : ports_array
        }

        report['raw_xml'] = ET.tostring(host).replace('\n', '')
        report['tags'] = get_tags(report)

        if check_report(report, send_all):
            reports.append(report)

        if len(reports) > 19:
            i = i + len(reports) 
            send_reports(reports, user, auth_hash, i)
            reports = []


    if len(reports):
        i = i + len(reports)
        send_reports(reports, user, auth_hash, i)

'''
    Scan and send reports
'''
def scan_target(target, scanlab_mode):
    target = escape_target(target)
    print("++ Start scanning " + target)
    if scanlab_mode == False:
        '''Do basic nmap scan'''
        nmap_str = "{0} {1} -oX {2}temp.xml {3}".format(nmap_bin, nmap_args, cwd, target)
        # nmap output goes to /dev/null
        if os.name == "posix": nmap_str += " > /dev/null" 
        os.system(nmap_str)
        parse_and_send(cwd+"temp.xml")
        os.system("rm "+cwd+"temp.xml")
    else:
        '''SCANLAB MODE'''
        for port in sl_ports:
            nmap_str = "{0} -p {1} --script {2} {3} -oX {4}temp.xml {5}".format(
                nmap_bin,
                port, 
                sl_modes[sl_ports[port]], 
                sl_nmap_args,
                cwd,
                target
            )

            if os.name == "posix": nmap_str += " > /dev/null" 
            os.system(nmap_str)
            parse_and_send(cwd+"temp.xml")
            os.system("rm "+cwd+"temp.xml")

    print("++ Finished scanning " + target)

'''
    XMPP bot class
'''
class SendMsgBot(sleekxmpp.ClientXMPP):
    def __init__(self, jid, password, recipient, message):
        sleekxmpp.ClientXMPP.__init__(self, jid, password)
        self.recipient = recipient
        self.msg = message
        self.add_event_handler("session_start", self.start)

    def start(self, event):
        self.send_presence()
        self.get_roster()
        self.send_message(mto=self.recipient,
                          mbody=self.msg,
                          mtype='chat')
        self.disconnect(wait=True)

def main():
    optp = OptionParser(usage="Usage: %prog [options]")
    optp.add_option("-f", "--file", dest="file_name", help="File upload - parse and upload xml file to ScanLab", metavar="FILE")
    optp.add_option("-d", "--remote", action="store_true", dest="remote",
            help="Remote client - get targets from ScanLab server, scan them and upload results to ScanLab")
    optp.add_option("-t", "--target", dest="target_string", help="Target scan - scan any nmap target (ex: IP, domain, subnet) and upload results to ScanLab",
            metavar="TARGET")
    optp.add_option("-l", "--list", dest="target_list", help="List scan - scan all targets from file and upload results to ScanLab", metavar="FILE")
    optp.add_option("--random", dest="random_num", help="Generate random targets - creates file targets.txt (Linux/Unix only)", metavar="NUM")
    optp.add_option("--domain", dest="domain", help="Generate random domains - creates file domains.txt (use --domain-length to specify domain length)",
            metavar="DOMAIN")
    optp.add_option("--domain-length", default=4, dest="domain_length", help="Length of domain to generate, default 4", metavar="NUM", type="int")

    opts, args = optp.parse_args()

    if opts.file_name is not None:
        '''
            PARSE AND SEND FROM FILE
        '''
        parse_and_send(opts.file_name)
        exit()
    elif opts.random_num is not None:
        '''
            GENERATE RANDOM TARGET LIST
        '''
        cmd = "{0} -iR {1} -n -sL | grep 'Nmap scan' | awk '{{print $5}}' | uniq -u > {2}targets.txt".format(
            nmap_bin,
            opts.random_num,
            cwd
        )
        os.system(cmd)
        print("++ File targets.txt is ready")
        exit()
    elif opts.domain is not None and opts.domain_length is not None:
        '''
            GENERATE RANDOM DOMAINS
        '''
        out_file = cwd + "domains.txt"
        f = open(out_file, 'w')
        words = (''.join(i) for i in itertools.product(string.ascii_lowercase, repeat = opts.domain_length))
        for w in words:
            f.write("{0}.{1}\n".format(w, opts.domain))
        print("++ File domains.txt is ready")
        exit()
    elif opts.target_string is not None:
        '''
            ONE TARGET FROM CLI
        '''
        scan_target(opts.target_string, scanlab_mode)
        exit()
    elif opts.target_list is not None:
        '''
            LIST OF TARGETS FROM FILE
        '''
        targets = open(opts.target_list).readlines()
        for target in targets:
            scan_target(target.strip(), scanlab_mode)
        exit()
    elif opts.remote is not None:
        '''
            GET TARGETS FROM SERVER
        '''
        xmpp_sent = False
        while True:
            target = get_target()

            if target == "2":
                print("!! invalid user/code OR api is disabled for you")
                exit()
            elif target == "0":
                '''NO TARGETS FOUND ON SERVER'''
                if xmpp_sent == False and xmpp_alerts == True:
                    '''Send xmpp message'''
                    xmpp_msg = "{0} finished all targets".format(xmpp_bot['node'])
                    xmpp = SendMsgBot(xmpp_bot['jid'], xmpp_bot['password'], xmpp_owner, xmpp_msg)
                    xmpp.register_plugin('xep_0030') 
                    xmpp.register_plugin('xep_0199')
                    if xmpp.connect():
                        xmpp.process(block=True)
                        print("++ xmpp alert sent")
                    else:
                        print("!! Unable to connect.")
                    xmpp_sent = True

                print("!! no targets found, will try again in 10 minutes")
                try:
                    time.sleep(600)
                except KeyboardInterrupt:
                    exit()
            else:
                '''GOT TARGET, SCAN IT'''
                xmpp_sent = False
                scan_target(target, scanlab_mode)

    else:
        '''
            PRINT HELP
        '''
        print("""                   _      _    
  ___ __ __ _ _ _ | |__ _| |__ 
 (_-</ _/ _` | ' \| / _` | '_ \\
 /__/\__\__,_|_||_|_\__,_|_.__/
""")
        optp.print_help()
        exit()

if __name__ == "__main__":
    main()
