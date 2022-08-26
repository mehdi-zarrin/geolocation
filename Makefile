export APP_IMAGE=mehdizarrin/geolocation

all:: build up test

build::
	docker build --tag ${APP_IMAGE} .
	docker run --rm \
		--volume "`pwd`:/app" \
		composer install --no-progress --prefer-dist --no-interaction

up::
	docker-compose up -d

down::
	docker-compose down --remove-orphans

test-api::
	sh ./run-behat.sh api

test-integration::
	sh ./run-behat.sh integration