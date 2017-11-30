#!/bin/bash

####################################
#
# Restore pta sql db out of dump from ftp
# Using /srv/backup as tranfer folder
# (run with sudo)
#
# @param $1: system abbr. to testore
#
####################################

start=`date +%s`
now=$(date +"%Y-%m-%d_%H-%M")

# check sys argument
if [ -z "$1" ]
  then
    echo "Pass system abbr. to clear as single argument."
    exit 1
  else
    sys=$1
fi


# Prepare Variables
db="pta_$sys-sql"

# read passwords from 700 files
ftppass=$(cat /etc/pta/ftpbackup)
sqlpass=$(cat /etc/pta/$sys-sql)

# Start script with standard message output
echo "START restore of $sys at $now"

lftp -c "open -u d27590a,$ftppass backup.contabo.net; mirror -v pta_$sys/ /srv/backup/pta_$sys;" \
&& cd /srv/backup/pta_$sys \
&& tfile=$(ls -t | head -1) \
&& tar -xzvf $tfile \
&& sfile=$(ls -t *.sql | head -1) \
&& docker cp $sfile $db:/tmp/$sfile \
&& docker exec $db bash -c "mysql -uroot -p$sqlpass postilotta_msgng < /tmp/$sfile" \
&& docker exec $db bash -c "rm /tmp/$sfile" \
&& rm -rf ./*

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END restore after $runtime sec"

# mirror OPTION --Remove-source-files
# mysql OPTION -v (produces too much output...)
