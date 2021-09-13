#!/bin/bash

docker-compose run --rm --user="$UID:$GID" insights_php php artisan migrate
