#!/bin/bash
DIR="$( cd "$( dirname "$0" )" && pwd )"
cd $DIR
device=(40 41 42 49 )
for i in "${device[@]}"
do
   for f in `find -name device_${i}.csv`
   do
      ./render-plot.sh $i $f
   done
done
