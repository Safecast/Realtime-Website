#!/bin/bash
current=`date  +%s`
cd /var/www/feeds/
chmod 666 device_*
/usr/bin/python updateFeeds.py
for i in device_*.csv; do python csv2xml.py $i;

last_modified=`stat -c "%Y" $i`
if [  $(($current-$last_modified)) -gt 1560 ]; then
        echo "$i Off line long time"
else

if [  $(($current-$last_modified)) -gt 660 ]; then
        echo "$i is Off line short time" | mail robouden@docomo.ne.jp;
else
echo " $i is On line"
fi
fi
done
