#!/usr/bin/env python
# -*- coding: UTF-8 -*-

import os, sys, re
import ConfigParser
from optparse import OptionParser
import uuid

# -----------------------------------------------------------------------------
# Main
# -----------------------------------------------------------------------------
if __name__ == '__main__':
  # Process command line options
  parser = OptionParser("Usage: csv2xml [options] <csv-filename>")
  (options, args) = parser.parse_args()

  if len(args) != 1:
      parser.error("Wrong number of arguments")

  filename = args[0]
  data = open(filename, 'r').readlines()

  header = """<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss">
  <updated>%s</updated>
  <title>Safecast Device %s RSS feed</title>
  <subtitle>Real-time radiation measured from device</subtitle>
  <link href="http://api.safecast.org/"/>
  <author><name>Safecast</name></author>
  <id>%s</id>
  <icon>http://blog.safecast.org/favicon.ico</icon>\n"""

  entry = """<entry><id>%s</id><title>%s CPM</title><updated>%s</updated><georss:point>%s %s</georss:point><summary>%s CPM</summary></entry>\n"""
  entrySv = """<entry><id>%s</id><title>%0.3f µSv/h</title><updated>%s</updated><georss:point>%s %s</georss:point><summary>%0.3f µSv/h</summary></entry>\n"""
  footer = """</feed>\n"""

  name, fileExtension = os.path.splitext(filename)
  output = open(name+".xml", "w")
  count = 0
  for line in data:
    line = line.strip()
    id,time,value,lon,lat,uniqueid = line.split(",")

    if (count == 0):
        lid,ltime,lvalue,llon,llat,luniqueid = data[-1].split(",")
        output.write(header % (ltime, lid, uuid.uuid5(uuid.NAMESPACE_DNS, name)))
      	usvh = float(value)/1
      	output.write(entrySv % (uniqueid, usvh, time, lat, lon, usvh))
    
    count += 1
  output.write(footer)
  output.close()