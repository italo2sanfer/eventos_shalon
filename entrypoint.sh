#!/bin/bash
set -e

/etc/init.d/php8.1-fpm start
/etc/init.d/nginx start
## Mysql
/usr/bin/mysqld_safe "${@:2}" 2>&1 >/dev/null | $ERR_LOGGER &
#/etc/init.d/mariadb start
##