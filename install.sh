#!/bin/bash

docker-compose up -d --force-recreate --build

cp ".env.example" ".env"

docker exec -it neayi_php sh -c "chmod -R 777 storage/"
docker exec -it neayi_php sh -c "composer install"
docker exec -it neayi_php sh -c "php artisan migrate"
docker exec -it neayi_php sh -c "php artisan key:generate"
docker exec -it neayi_php sh -c "vendor/bin/phpunit tests/Unit"
docker exec -it neayi_php sh -c "vendor/bin/phpunit tests/Unit/ -c phpunit-ti-domain-sql.xml"

echo ""
echo "You should add to your hosts : 127.0.0.1 dev.core.tripleperformance.com"

echo ""
echo "You can visit : http://dev.core.tripleperformance.com:8008"

docker-compose up -d

docker network create triple-network
docker network connect triple-network tripleperformance-sql
docker network connect triple-network neayi_php
