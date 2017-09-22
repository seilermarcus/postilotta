#!/bin/bash

####################################################
#
# Log in to ftp-backup server and prompt remote cli
# (run with sudo)
#
####################################################

ftppass=$(cat /etc/pta/ftpbackup)

lftp -e "open -u d27590a,$ftppass backup.contabo.net;"
