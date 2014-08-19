#!/usr/bin/env python
import urllib
import urllib2
from hashlib import sha1
from optparse import OptionParser
from config import *

auth_hash = sha1(sha1(code).hexdigest() + salt).hexdigest()

def get_opener(key, query=False):
    opener = urllib2.build_opener()
    opener.addheaders.append(('Cookie', 'PHPSESSID=' + key))

    if query is not False:
        query = urllib.urlencode({ 'q': query })
        return query, opener
    else:
        return opener

def get_login():
    params = { 'code': auth_hash, 'user': user }
    data = urllib.urlencode(params)
    req = urllib2.Request(url=api_url+"login", data=data)

    try:
        content = urllib2.urlopen(req).read()
    except:
        print "Error: wrong username/password or api disabled"
        exit()

    return content
    
def get_ip_list(query, key):
    query, opener = get_opener(key, query)
    try:
        content = opener.open(api_url + "iplist?" + query).read()
    except:
        print "Error: your key is invalid or something went wrong"
        exit()

    return content

def get_json(query, key):
    query, opener = get_opener(key, query)
    try:
        content = opener.open(api_url + "json_export?" + query).read()
    except:
        print "Error: your key is invalid or something went wrong"
        exit()

    return content


def get_xml(query, key):
    query, opener = get_opener(key, query)
    try:
        content = opener.open(api_url + "xml_export?" + query).read()
    except:
        print "Error: your key is invalid or something went wrong"
        exit()

    return content

def main():
    optp = OptionParser(usage="Usage: %prog [options]")

    optp.add_option("-a", "--get-key", action="store_true", dest="get_key", help="Get auth key", default=False)
    optp.add_option("-k", "--auth-key", dest="auth_key", help="Auth key [REQUIRED]", metavar="KEY", default=False)
    optp.add_option("-q", "--query", dest="search_query", help="Search query [REQUIRED]", metavar="QUERY", default=False)
    optp.add_option("-l", "--ip-list", action="store_true", dest="get_ip_list", help="Get raw ip list", default=False)
    optp.add_option("-j", "--json", action="store_true", dest="get_json", help="Get json file", default=False)
    optp.add_option("-x", "--xml", action="store_true", dest="get_xml", help="Get xml file", default=False)

    opts, args = optp.parse_args()

    if opts.auth_key is False:
        if opts.get_key is True:
            print get_login()
            exit()
        else:
            optp.print_help()
            exit()
    else:
        if opts.search_query is not False:
            search_query = opts.search_query
        else:
            print "ERROR: you did not specified search query!"
            exit()

        if opts.get_ip_list is True:
            print get_ip_list(search_query, opts.auth_key).strip()
            exit()
        elif opts.get_json is True:
            print get_json(search_query, opts.auth_key).strip()
            exit()
        elif opts.get_xml is True:
            print get_xml(search_query, opts.auth_key).strip()
            exit()

        exit()


if __name__ == "__main__":
    main()
