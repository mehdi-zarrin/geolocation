export APP_IMAGE=mehdizarrin/geolocation
EXEC=docker exec nt_coordinates_resolver_php
CONSOLE=$(EXEC) bin/console
all: build up db-init

build:
	docker build --tag ${APP_IMAGE} .
	docker run --rm \
		--volume "`pwd`:/app" \
		composer install --no-progress --prefer-dist --no-interaction

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

test-api:
	sh ./run-behat.sh api

test-integration:
	sh ./run-behat.sh integration

wait-for-db:
	$(EXEC) php -r "set_time_limit(60);for(;;){if(@fsockopen('nt_coordinates_resolver_mysql',3306)){break;}echo \"Waiting for MySQL\n\";sleep(1);}"

db-init: wait-for-db
	$(CONSOLE) doctrine:database:create --if-not-exists --no-debug
	$(CONSOLE) doctrine:schema:update --force

db-migrate: wait-for-db
	$(EXEC) bin/console doctrine:migration:migrate -n --no-debug