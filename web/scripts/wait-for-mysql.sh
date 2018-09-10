#!/bin/bash
# wait-for-mysql.sh

set -e

host="$1"
shift
cmd="$@"

until mysql  -h mysql -u root -e "select 1" || echo down; do
  >&2 echo "MySql is unavailable - sleeping"
  sleep 1
done

>&2 echo "MySql is up - executing command"
exec $cmd

