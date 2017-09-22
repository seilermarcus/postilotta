#!/bin/bash

####################################
#
# Build pta dev instance
# (run with sudo)
#
# @param [$1]: system abbr. to build
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

# Clear up before build

./batch_clearall.sh $sys

# Build dev environment
./batch_build.sh $sys 'TRUE'

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END build of $sys after $runtime sec"
