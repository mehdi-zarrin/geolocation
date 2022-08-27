[![Build Status](https://app.travis-ci.com/mehdi-zarrin/geolocation.svg?branch=master)](https://app.travis-ci.com/mehdi-zarrin/geolocation)

# Coordinates resolver


## How to start the project

These are following steps to setup project:

```
cp .env.dist .env
```
then inside .env file, replace values of `GOOGLE_GEOCODING_API_KEY` and `HEREMAPS_GEOCODING_API_KEY` with correct values.

then to prepare docker environment just run the following command in the project directory:
```
make
```

As you can see there is a `build passing` badge which means the project is built and tested by travis ci, however, if you want to run the test in your local machine just run the following commands:
```
make test
```
or to run them separately run:
```
make api-test
make integration-test
```
in the api test the GMaps and HMaps are mocked using wiremock.

then go to `http://localhost/coordinates` and it should return
```
{"lat":52.4968646,"lng":13.3257589}
```

Finally to shutdown the app run:
```
make down 
```

