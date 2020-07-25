#!/usr/bin/env bash

docker-compose exec php-fpm /usr/src/app/bin/console $*
