#!/usr/bin/env bash

# It's a bash sort of a day
grep -oE 'mul\([0-9]{1,3}\,[0-9]{1,3})' input | sed -r 's/(mul\(|\))//g' | awk -F, '{SUM+=$1*$2} END { print SUM}'
