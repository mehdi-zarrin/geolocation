Feature:
    In order to prove that the coordinate endpoint works properly

    Background:
        Given clean wiremock

    @api
    Scenario: The given query parameters doesn't exist in cache
        Given wiremock stubs from "geocoding/gmaps.json"
        And wiremock stubs from "geocoding/hmaps.json"
        When I send a "GET" request to "/coordinates" with parameters:
            | key          | value              |
            | countryCode  | de                 |
            | city         | berlin             |
            | street       | pariser street 11  |
            | postcode     | 10719              |
        Then the response should be in JSON
        And the response status code should be 200
        And the JSON node "data.lat" should exist
        And the JSON node "data.lng" should exist
        And Instance of "\App\Entity\ResolvedAddress" with "countryCode" equal to "de" contains the following data:
        | countryCode | city       |
        | de          | berlin     |

    @api
    Scenario: The given parameters exist in cache and both provider are down
        Given wiremock stubs from "geocoding/gmaps_500.json"
        And wiremock stubs from "geocoding/hmaps_500.json"
        And Created entities of "\App\Entity\ResolvedAddress" with
            | countryCode | city   | street            | postcode | lat | lng |
            | de          | berlin | pariser street 11 | 10719    | 123 | 123 |
        When I send a "GET" request to "/coordinates" with parameters:
            | key          | value              |
            | countryCode  | de                 |
            | city         | berlin             |
            | street       | pariser street 11  |
            | postcode     | 10719              |
        Then the response status code should be 200
        And the JSON should be equal to:
        """
            {
                "data": {
                    "lat": 123,
                    "lng": 123
                }
            }
        """

    @api
    Scenario: The given parameters in cache but lat and lng is missing and both providers are down
        Given wiremock stubs from "geocoding/gmaps_500.json"
        And wiremock stubs from "geocoding/hmaps_500.json"
        And Created entities of "\App\Entity\ResolvedAddress" with
            | countryCode | city   | street            | postcode | lat  | lng  |
            | de          | berlin | pariser street 11 | 10719    | null | null |
        When I send a "GET" request to "/coordinates" with parameters:
            | key          | value              |
            | countryCode  | de                 |
            | city         | berlin             |
            | street       | pariser street 11  |
            | postcode     | 10719              |
        Then the response status code should be 200
        And the JSON should be equal to:
        """
            {
                "data": []
            }
        """
        
    @api
    Scenario: There is nothing in cache and both providers are down
        Given wiremock stubs from "geocoding/gmaps_500.json"
        And wiremock stubs from "geocoding/hmaps_500.json"
        When I send a "GET" request to "/coordinates" with parameters:
            | key          | value              |
            | countryCode  | de                 |
            | city         | berlin             |
            | street       | pariser street 11  |
            | postcode     | 10719              |
        Then the response status code should be 200
        And the JSON should be equal to:
        """
            {
                "data": []
            }
        """
        And Instance of "\App\Entity\ResolvedAddress" with "countryCode" equal to "de" contains the following data:
            | countryCode | city   | street            | postcode | lat  | lng  |
            | de          | berlin | pariser street 11 | 10719    | null | null |

    @api
    Scenario: There is nothing in cache and only one provider (gmaps) is working
        Given wiremock stubs from "geocoding/gmaps.json"
        And wiremock stubs from "geocoding/hmaps_500.json"
        When I send a "GET" request to "/coordinates" with parameters:
            | key          | value              |
            | countryCode  | de                 |
            | city         | berlin             |
            | street       | pariser street 11  |
            | postcode     | 10719              |
        Then the response status code should be 200
        And the JSON node "data.lat" should exist
        And the JSON node "data.lng" should exist
        And Instance of "\App\Entity\ResolvedAddress" with "countryCode" equal to "de" contains the following data:
            | countryCode | city   | street            | postcode |
            | de          | berlin | pariser street 11 | 10719    |

    @api
    Scenario: There is nothing in cache and only one provider (hmaps) is working
        Given wiremock stubs from "geocoding/gmaps_500.json"
        And wiremock stubs from "geocoding/hmaps.json"
        When I send a "GET" request to "/coordinates" with parameters:
            | key          | value              |
            | countryCode  | de                 |
            | city         | berlin             |
            | street       | pariser street 11  |
            | postcode     | 10719              |
        Then the response status code should be 200
        And the JSON node "data.lat" should exist
        And the JSON node "data.lng" should exist
        And Instance of "\App\Entity\ResolvedAddress" with "countryCode" equal to "de" contains the following data:
            | countryCode | city   | street            | postcode |
            | de          | berlin | pariser street 11 | 10719    |

    @api
    Scenario: There should be proper validations in case the query parameters are missing
        When I send a "GET" request to "/coordinates"
        Then the response status code should be 400
        And the JSON node "validationErrors" should exist
        And the JSON node "validationErrors[0].message" should exist
        And the JSON node "validationErrors[0].field" should exist


    @integration
    Scenario: Test the actual functionality against real api
        When I send a "GET" request to "/coordinates" with parameters:
            | key          | value              |
            | countryCode  | de                 |
            | city         | berlin             |
            | street       | pariser street 11  |
            | postcode     | 10719              |
        Then the response should be in JSON

        And the response status code should be 200
        And the JSON node "data.lat" should exist
        And the JSON node "data.lng" should exist
        And Instance of "\App\Entity\ResolvedAddress" with "countryCode" equal to "de" contains the following data:
            | countryCode | city      |
            | de          | berlin    |