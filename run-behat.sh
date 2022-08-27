#!/bin/bash
set -e

DOCKER_COMPOSE="${DOCKER_COMPOSE:-"docker-compose -p test-geo-location -f docker-compose-test.yml"}"
DOCKER_COMPOSE_EXEC="$DOCKER_COMPOSE exec -T "

echo "Executing 'docker-compose down' just to be sure that nothing left from previous builds"
$DOCKER_COMPOSE down --remove-orphans
echo "Starting containers"
$DOCKER_COMPOSE up -d

echo "Warming up cache"
$DOCKER_COMPOSE_EXEC php-test ./bin/console cache:warmup
echo "Waiting for mysql"
$DOCKER_COMPOSE_EXEC php-test php -r "set_time_limit(60);for(;;){if(@fsockopen('test_coordinates_resolver_mysql',3306)){break;}echo \"Waiting for MySQL\n\";sleep(1);}"
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
  $DOCKER_COMPOSE_EXEC php-test bash -c "./vendor/bin/behat --suite=api"
fi

echo "All tests successfully passed!"
echo "Stopping containers, removing volume"
$DOCKER_COMPOSE down -v
