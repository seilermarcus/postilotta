#!/bin/bash

####################################################
#
# Log in to ftp-backup server and prompt remote cli
# (run with sudo)
#
# @param $1: server abbr. (pta, yo)
#
####################################################

# check sys argument
if [ -z "$1" ]
  then
    echo "Pass server abbr. pta or yo as argument."
    exit 1
  else
    if [ $1 = "pta" ]
    then
      ftpusr="d27590b"
    else
      if [ $1 = "yo"]
      then
        ftpusr="d27590a"
      fi
    fi
fi

ftppass=$(cat /etc/pta/ftpbackup)

lftp -e "open -u $ftpusr,$ftppass backup.contabo.net;"
