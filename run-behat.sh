#!/bin/bash

DOCKER_COMPOSE="${DOCKER_COMPOSE:-"docker-compose -p test-technical-task -f docker-compose-test.yml"}"
DOCKER_COMPOSE_EXEC="$DOCKER_COMPOSE exec -T "

echo "Executing 'docker-compose down' just to be sure that nothing left from previous builds"
$DOCKER_COMPOSE down --remove-orphans
echo "Starting containers"
$DOCKER_COMPOSE up -d

echo "Warming up cache"
$DOCKER_COMPOSE_EXEC php-test ./bin/console cache:warmup
echo "Waiting for mysql"
$DOCKER_COMPOSE_EXEC mysql-test bash -c "while ! mysql -h localhost -u root -proot -e \"show databases\"; do sleep 1 ;done"
echo "Executing migrations"
$DOCKER_COMPOSE_EXEC php-test ./bin/console doctrine:schema:create -n
echo "Validating schema"
$DOCKER_COMPOSE_EXEC php-test ./bin/console doctrine:schema:validate -n



if [[ $1 == "integration" ]]
then
echo "Running integration tests"
$DOCKER_COMPOSE_EXEC php-test bash -c "
  export GOOGLE_GEOCODING_HOST=https://maps.googleapis.com &&
  export HEREMAPS_GEOCODING_HOST=https://geocode.search.hereapi.com &&
  ./vendor/bin/behat --suite=integration
  "
else
  echo "Running api tests"
  $DOCKER_COMPOSE_EXEC php-test bash -c "APP_ENV=test ./vendor/bin/behat --suite=api"
fi

echo "All tests successfully passed!"
echo "Stopping containers, removing volume"
$DOCKER_COMPOSE down -v
