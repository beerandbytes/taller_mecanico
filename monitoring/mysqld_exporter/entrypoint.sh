#!/bin/sh

# Create my.cnf file from environment variables
cat <<EOF > /tmp/.my.cnf
[client]
host=${MYSQL_HOST}
port=${MYSQL_PORT}
user=root
password=${MYSQL_ROOT_PASSWORD}
EOF

chmod 600 /tmp/.my.cnf

# Start exporter pointing to the config file
exec /bin/mysqld_exporter --config.my-cnf=/tmp/.my.cnf