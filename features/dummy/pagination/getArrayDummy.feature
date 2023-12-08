Feature: Get Dummy Collection resource

  @http
  Scenario: Get a dummy collection resource
    When one get a dummy array
    Then success
    And get dummy array response should be:
    """
    {
      "data": [
        {
          "id": 0,
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
        },
        {
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
        },
        {
          "id": 2,
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
        },
        {
          "id": 3,
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
        },
        {
          "id": 4,
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
      ]
    }
    """

  @swagger
  Scenario: Build a dummy operation
    When one get the swagger json
    Then get dummy array should be configured

