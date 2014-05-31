#!/bin/bash
device_no=$1

gnuplot << EOF
   set title "${device_no} Sensor Data"
   set datafile separator ","
   set term png size 1200,400
   set output "device_${device_no}.png"
   set key left top
   set xdata time
   set grid
   set timefmt "%Y.%m.%d"
   plot "device_${device_no}.csv_ok" using 1:2
EOF
