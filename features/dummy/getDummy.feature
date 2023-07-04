Feature: Get a Dummy resource

  @http
  Scenario: Get a resource
    When one get a dummy with:
      | id     | Cirano    |
    Then success
    And get dummy response should be:
    """
    {
      "data": {
        "id": 1,
        "firstName": "Pink",
        "lastName": "Floyd",
        "age": "25-05-1993",
        "ulid": "01H3VY5WDTNNK2MDBSD23EK0HS",
        "uuid": "de0215dd-23a6-42ca-8732-b341da0d07d9",
        "address": {
            "street": "127 avenue of the street",
            "zipCode": "13100",
            "city": "California",
            "country": "US"
        }
      }
    }
    """

  @swagger
  Scenario: Get dummy schema
    When one get the swagger json
    Then get dummy should be configured

