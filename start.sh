. ./config.sh
. ./shutdown.sh

appstart() {
  xdg-open "http://192.168.1.100/"
  docker_compose
}

docker_compose() {
  docker compose -f $configfile up
}

id=$(docker ps -aq -f name=$appname)

if [ "$2" = "--build" ]; then
  docker compose -f $configfile build
fi

if [ -z "$id" ]; then
  # this ensures that every container belonging to this application
  # will be shut down at startup
  appstop $imagename
  appstart
else
  appstart
fi
