#!/bin/bash

#read -p "System (dev, test,...): " syst
#read -p "Let's Encrypt certificate REUSE_KEY (TRUE / FALSE): " reuse
./batch_build.sh ${PWD##*/} 'TRUE' '123456'
