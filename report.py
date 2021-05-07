#! /usr/bin/python3

# import libraries
from subprocess import run, PIPE
import json
import requests
import datetime

# initialize server and interface values
server = "http://192.168.0.201/pi/check_in.php"
interface = "eth0" # "wlan0"

# call ifconfing on an interface
def ip_info(interface):
    # run the command and capture its output
    cmd = ["/sbin/ifconfig", interface]
    pipe = run(cmd, stdout=PIPE, stderr=PIPE)
    ip_out = pipe.stdout

    details = {}

    # if there is an output
    if ip_out:
        # decode it and split it at new lines
        output = ip_out.decode("utf-8").split("\n")

        # parse results
        details = {}
        details['adapter'] = output[0].strip().split(":")[0]
        details['ip'] = output[1].strip().split()[1]
        details['mac'] = output[3].strip().split()[1]

    return details

# call hostname and whoami
def host_info():
    # run hostname and capture output
    cmd = ["hostname"]
    pipe = run(cmd, stdout=PIPE, stderr=PIPE)

    # initialize dictionary and parse hostname output
    details = {}
    details["hostname"] = pipe.stdout.decode("utf-8").strip()

    # run whoami and capture output
    cmd = ["whoami"]
    pipe = run(cmd, stdout=PIPE, stderr=PIPE)

    # parse whoami output
    details["user"] = pipe.stdout.decode("utf-8").strip()

    return details

# call functions to get info
host = host_info()
net = ip_info(interface)

# merge dictionaries of results
details = { **host, **net}

# dump the dictionary into json format
j = json.dumps(details)

# print(server, details, j)

# post json to php script on server
r = requests.post(url = server, data = details)
print(r.text, datetime.datetime.now()) # debug print request output

# dump the dictionary into json format with indents
j = json.dumps(details, indent=4)

# open json file and write formatted json to file
json_file = open("/home/pi/admin/details.json", "w")
json_file.write(j)
json_file.close()
