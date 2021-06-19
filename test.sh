#!/bin/bash

php artisan test

docker-compose run --rm --user="$UID:$GID" insights_php vendor/bin/phpunit tests/Integration/Repositories -c phpunit-ti-domain-sql.xml
