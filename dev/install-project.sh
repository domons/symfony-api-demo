#!/bin/bash

set -e

function info {
    printf "\e[34m=> $1\e[0m\n"
}

info "Start installing project"
cd docker/dev

info "Removing previous generated project files"
docker compose down
rm -rf ../app/var/log/*
rm -rf ../app/var/cache/*

info "Running docker"
docker compose up -d --build

info "Waiting for docker to start"
sleep 5

info "Composer installation without scripts"
docker compose exec -u www-data -e COMPOSER_MEMORY_LIMIT=-1 php composer --ignore-platform-reqs install --no-scripts

info "Migration database"
docker compose exec -u www-data php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

info "Clear cache"
docker compose exec -u www-data php bin/console cache:clear

info "Done"
