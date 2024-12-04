#!/usr/bin/env bash

# Is this kind of a lame way to do it? Yes!
# Do I care after I spent way too long trying to be smart on yesterday's puzzle? Nope!

ENABLED=1

grep -oE "(do\(\)|don't\(\)|mul\([0-9]{1,3}\,[0-9]{1,3}\))" input |
while read line; do

    if [ "$line" = "don't()" ]; then
        ENABLED=0
        continue
    fi

    if [ "$line" = "do()" ]; then
        ENABLED=1
        continue
    fi

    if [ "$ENABLED" -eq "0" ]; then
        continue
    fi

    echo "$line" | sed -r 's/(mul\(|\))//g'

done | awk -F, '{SUM+=$1*$2} END { print SUM}'
