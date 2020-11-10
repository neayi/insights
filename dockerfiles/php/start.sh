#!/usr/bin/env bash

php-fpm -D -O
nginx -g 'daemon off;'
