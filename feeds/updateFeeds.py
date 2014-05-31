#!/usr/bin/env python
# -*- coding: UTF-8 -*-

from optparse import OptionParser
import urllib
import datetime
import uuid

# -----------------------------------------------------------------------------
# Main
# -----------------------------------------------------------------------------
if __name__ == '__main__':
  # Process command line options
  parser = OptionParser("Usage: updateFeeds.py [options]")
  (options, args) = parser.parse_args()

  now = datetime.datetime.utcnow()
  d = datetime.timedelta(minutes = 5)
  before = now - d
  after = now

  REQUEST = 'http://api.safecast.org/en-US/measurements?captured_after="%s"&captured_before="%s"&format=csv'
  print REQUEST % (before.strftime("%Y/%m/%d %H:%M:%S"), after.strftime("%Y/%m/%d %H:%M:%S"))
  data = urllib.urlopen(REQUEST % (before.strftime("%Y/%m/%d %H:%M:%S"), after.strftime("%Y/%m/%d %H:%M:%S"))).readlines()

  for l in data:
    t, lat, lon, value, unit, location, device_id, md5, height, surface, radiation, upload, loader_id = l.strip().split(",")
    try:
      dtime = datetime.datetime.strptime(t, '%Y-%m-%d %H:%M:%S UTC')
    except:
      continue
    usvh = float(value)/334.0 
    output = open("device_%s.csv" % device_id, "w")
    output.write("%s,%s,%.3f,%s,%s,%s\n" %(device_id, dtime.strftime("%Y-%m-%dT%H:%M:%SZ"),usvh,lon,lat,uuid.uuid1().urn))
    output.close()
