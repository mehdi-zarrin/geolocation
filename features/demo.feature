Feature:
    In order to prove that the coordinate endpoint works properly

    Background:
        Given clean wiremock

    @coordinates
    Scenario:
        When I send a "GET" request to "/coordinates"
        Then the response should be in JSON
        And the response status code should be 200
        And the JSON should be equal to:
    """
      {
        "lat": 55.90742079144914,
        "lng": 21.135541627577837
      }
    """
    And Instance of "\App\Entity\ResolvedAddress" with "countryCode" equal to "lt" contains the following data:
        | countryCode | city      |
        | lt         | vilnius   |

    @gmap
    Scenario:
        Given wiremock stubs from "geocoding/gmaps.json"
        When I send a "GET" request to "/gmaps" with parameters:
            | key     | value         |
            | country | lt            |
            | city    | vilnius       |
            | street  | jasinskio 16  |
            | postcode | 01112        |
        Then the response should be in JSON
        And the response status code should be 200
        And the JSON should be equal to:
    """
        {
            "lat": 54.6878265,
            "lng": 25.2609295
        }
    """

    @hmaps
    Scenario:
        Given wiremock stubs from "geocoding/hmaps.json"
        When I send a "GET" request to "/hmaps" with parameters:
            | key     | value         |
            | country | ir            |
            | city    | vilnius       |
            | street  | jasinskio 16  |
            | postcode | 01112        |
        Then the response should be in JSON
        And the response status code should be 200
        And the JSON should be equal to:
    """
        {
            "lat": 54.68781,
            "lng": 25.26095
        }
    """