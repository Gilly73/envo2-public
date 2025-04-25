#!/bin/bash
# wait-for-mysql.sh
until nc -z mysql 3306; do
  echo "Waiting for MySQL..."
  sleep 2
done
echo "MySQL is up - starting product"
exec "$@"
