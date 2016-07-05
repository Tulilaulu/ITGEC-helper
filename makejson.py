#!/usr/bin/python
import json

# Open a file
low = open("low.txt", "r+")
high = open("high.txt", "r+")
double = open("double.txt", "r+")

ob = {
  "low":{},
  "high":{},
  "double":{}
  }

for row in low:
  a = row.split("|")
  a[0] = a[0].strip()
  a[1] = a[1].strip()
  if (a[1] not in ob["low"]):
    ob["low"][a[1]] = []
  ob["low"][a[1]].append(a[0])

for row in high:
  a = row.split("|")
  a[0] = a[0].strip()
  a[1] = a[1].strip()
#  a[2] = a[2].strip()
  if (a[1] not in ob["high"]):
    ob["high"][a[1]] = [] 
  ob["high"][a[1]].append(a[0])
#For 2015 system:
#    ob["high"][a[1]]["L"] = []
#    ob["high"][a[1]]["H"] = []
#  if (a[2] == "H"):
#    ob["high"][a[1]]["H"].append(a[0])
#  else:
#    ob["high"][a[1]]["L"].append(a[0])


for row in double:
  a = row.split("|")
  a[0] = a[0].strip()
  a[1] = a[1].strip()
  if (a[1] not in ob["double"]):
    ob["double"][a[1]] = []
  ob["double"][a[1]].append(a[0])


j = open("biisit.json", "w")
j.write(json.dumps(ob))

j.close()
double.close()
high.close()
low.close()
