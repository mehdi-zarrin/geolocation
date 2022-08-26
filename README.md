[![Build Status](https://app.travis-ci.com/mehdi-zarrin/geolocation.svg?branch=master)](https://app.travis-ci.com/mehdi-zarrin/geolocation)

# Coordinates resolver


## How to start project

These are following steps to setup project:

```
cp .env.dist .env
```
then inside of .env file, replace set correct values for GOOGLE_GEOCODING_API_KEY and HEREMAPS_GEOCODING_API_KEY variables.

then prepare docker environment:
```
make build
make up
docker-compose run php bash
```

final project steps inside of docker container:
```
bin/console doctrine:database:create
bin/console doctrine:schema:create
```

then go to `http://localhost/coordinates` and it should return 

```
{"lat":52.4968646,"lng":13.3257589}
```

