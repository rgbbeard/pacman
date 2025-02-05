#!/bin/bash

base="$(dirname $(readlink -f $0))"

. "$base/run_as_root.sh"
. "$base/config.sh"

xdg-open "http://$hostname"
run_as_root php -S "$hostname:$port"