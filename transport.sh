#!/bin/bash

tpwd=$PWD
dir=${PWD##*/}
date=$(date -u)

read -p "Folder to transport to: " fldr

read -p "New Version? (like V2.5.12): " version
read -p "Version one-liner: " oneliner


out="[$date] $version  : $oneliner"
echo $out >> "version.txt"

rsync -av --progress www/ ../$fldr/www/ --exclude=sql-ip.inc

echo 'done'
