DOCKER_EXEC:=cd docker/dev && docker compose exec -u www-data php

php-cli:
	${DOCKER_EXEC} bash

phpunit:
	${DOCKER_EXEC} bin/phpunit

bin/console:
	${DOCKER_EXEC} bin/console $(filter-out $@,$(MAKECMDGOALS))

server-start:
	cd docker/dev && docker compose up -d

server-stop:
	cd docker/dev && docker compose stop

cc:
	${DOCKER_EXEC} bin/console cache:clear --no-warmup

install:
	bash ./dev/install-project.sh

psalm:
	${DOCKER_EXEC} php -d memory_limit=4G vendor/bin/psalm --taint-analysis

phpmd:
	${DOCKER_EXEC} php -d memory_limit=4G vendor/bin/phpmd src text phpmd.xml

cs-check:
	${DOCKER_EXEC} vendor/bin/phpcs --standard=PSR12 -s --colors --extensions=php src

cs-fix:
	${DOCKER_EXEC} vendor/bin/php-cs-fixer fix --verbose

phpstan:
	${DOCKER_EXEC} php -d memory_limit=4G vendor/bin/phpstan analyse src

before-push:
	$(MAKE) cs-fix
	$(MAKE) cs-check
	$(MAKE) phpstan
	$(MAKE) psalm
	$(MAKE) phpmd
	$(MAKE) phpunit
