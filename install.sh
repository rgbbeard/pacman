#!/bin/bash

base="$(dirname $(readlink -f $0))"

. "$base/run_as_root.sh"
. "$base/config.sh"

filename="/etc/hosts"

if [ ! -f "$filename" ]; then
    echo "Error: File '$filename' not found."
    exit 1
fi

if grep -q "$host" "$filename"; then
    exec start.sh
else
    # add host to the list
    echo -e "\n$ipaddr $host" >> "/etc/hosts"
fi