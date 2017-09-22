#!/bin/bash

####################################
#
# Clear pta dev instance
#
# @param [$1]: system abbr. to clear
#
####################################
start=`date +%s`
now=$(date +"%Y-%m-%d_%H-%M")

# use argument or default
if [ -z "$1" ]
  then
    sys="dev"
  else
    sys=$1
fi

# Start script with standard message output
echo "START build of $sys at $now sec"

./batch_clearall.sh $sys

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END build of $sys after $runtime sec"
