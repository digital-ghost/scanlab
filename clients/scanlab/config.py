##
#   Basic client settings
##

# Your username
user = 'admin'

# Your password
code = 'letadminin'

# URL to scanlab API
api_url = 'http://private.scanlab.project.li/api/'

# Set true if you want to do all the requests to ScanLab server with TOR
# (NMAP still will be running withought TOR!)
use_tor = False

# Compress big files and save them to archive folder, instead of sending to server
# Useful option for clients with low RAM
compress_large = False

# Maximum file size of report to be parsed (bytes)
max_file_size = 10240000

##
#   Jabber notification settings
##

# Set False if you do not want to get alerts via Jabber
xmpp_alerts = False

# XMPP bot configuration
xmpp_bot = {
    "jid":"node3@jabber.de", # Your jabber bot jid
    "password":"botPassw0rd", # password
    "node":"node3" # Set any name to your bot (useful if you have many)
}

# Your jabber ID 
xmpp_owner = "vasya@jabber.de"

##
#   Advanced settings
#   You can stop configuring now. Defaults are fine too.
##

# Salt. Dont touch this.
salt = 'lab'

# GeoIP database file location. Default for Ubuntu/Debian
# apt-get install geoip-database
# OR you can download fresh DB from maxmind.com
geoip_file = '/usr/share/GeoIP/GeoIP.dat'

# Nmap binary file. Dont touch this.
nmap_bin = 'nmap'

# NSE args. Do not touch this
nse_args = "http.useragent=Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0"
# configure bruteforce
nse_args += ",userdb=users.txt,passdb=passwords.txt,http-brute.path=/"

# Nmap arguments for targets
nmap_args = '-n -T4 -F'
nmap_args += ' --script default,http-headers,banner,nfs-ls,mysql-empty-password'
# comment next line to disable bruteforce
#nmap_args += ' --script http-brute,ftp-brute,smb-brute,ftp-anon'
nmap_args += " --script-args='{0}'".format(nse_args)

# True if you want to save junk (dead host or no open ports) reports in your DB. Do not change it.
send_all = False

##
#   SCANLAB MODE settings
##

# Use advanced "scanlab" mode
# (previously "DeepWebEXplorer")
# NMAP will scan each target for a single port with scripts, defined in modes
# Scan HUGE subnets RAPIDLY and with great PROFIT!
scanlab_mode = False

# Nmap arguments for scanlab mode
sl_nmap_args = "-n -T4 -Pn"
# comment next line to disable bruteforce
#sl_nmap_args += " --script http-brute,ftp-brute,smb-brute,telnet-brute"
sl_nmap_args += " --script-args='{0}'".format(nse_args)

# Each "mode" has it's own NSE scripts collection
sl_modes = {
    "http":"http-headers,http-title,http-generator", 
    "ftp":"ftp-anon,banner",
    "smb":"smb-os-discovery", # smb-enum-shares is slower, but cool
    "telnet":"banner"
}

# Ports to "modes" binding
# Put here ports you want to scan and thier "mode" 
sl_ports = {
    "80":"http",
#    "81":"http",
#    "443":"http",
#    "8008":"http",
    "8080":"http",
#    "8081":"http",
    "21":"ftp",
#    "139":"smb",
    "445":"smb",
#    "23":"telnet"
}

##
#   Define tags
##

# Scanlab has really basic tag engine
# TAGNAME : [list of phrases in xml]
sl_tags = {
    "camera":["ipcam","IPCam", "Ipcam", "netcam", "Netcam", "camera", "Camera", 
        "CAMERA", "AXIS", "webcamXP", "ATZ", "IQhttpD", "Avtech", "DCS-930L", "D-Link Internet", "WEBCAM"],        
    "cisco":["cisco", "level_15_access"],
    "anonftp":["Anonymous user logged in", "Anonymous access granted", "Anonymous FTP login allowed"],
    "windows":["IIS", "Microsoft Windows"],
    "linux":["Ubuntu", "Debian", "RHEL"],
    "router":["wireless", "ADSL", "DSL Router",
        "TD-W8101G", "admin/1234", "Welcome to ASUS", "OpenWRT", "Linksys", "ZXV10", "NETGEAR","Netgear"],
    "printer":["LaserJet"],
    "media":["Dreambox", "dreambox"],
    "scada":["Modicon", "SCADA", "AKCP", "WinCE", "IPC@CHIP"]
}
