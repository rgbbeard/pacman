run_as_root() {
  [ "${UID}" -ne "0" ] && sudo -p "Enter password for root: " "$@" || "$@"
}