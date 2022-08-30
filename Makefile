export APP_IMAGE=mehdizarrin/geolocation
EXEC=docker exec nt_coordinates_resolver_php
CONSOLE=$(EXEC) bin/console
all: build up db-init db-migrate

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

test: test-api test-integration

wait-for-db:
	$(EXEC) php -r "set_time_limit(60);for(;;){if(@fsockopen('nt_coordinates_resolver_mysql',3306)){break;}echo \"Waiting for MySQL\n\";sleep(1);}"

db-init: wait-for-db
	$(CONSOLE) doctrine:database:create --if-not-exists --no-debug

db-migrate: wait-for-db
	$(CONSOLE) doctrine:migration:migrate -n --no-debug

analyse-code:
	docker build --tag ${APP_IMAGE} .
	docker run --tty --rm \
		--volume "`pwd`:/app" \
		${APP_IMAGE} \
		bash -c \
		'composer install --no-progress --prefer-dist --no-interaction \
		&& vendor/bin/phpcs \
		&& vendor/bin/phpstan analyse src \
		'