#!/bin/bash

##########################################
#
# Move pta project folder to source of v03
# prep for jenkins build of v03
#
####################################
start=`date +%s`
now=$(date +"%Y-%m-%d_%H-%M")

# Start script with standard message output
echo "START transport to v03 at $now sec"

rsync -av --progress ../ /var/postilotta/prototype/.

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END transport to v03 after $runtime sec"
