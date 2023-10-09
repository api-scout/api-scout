Feature: Get Dummy Collection resource

  @http
  Scenario: Get a dummy collection resource
    When one get a dummy collection with "dummy" at page 1
    Then success
    And get dummy collection response should be:
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
        },
        {
          "id": 5,
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
          "id": 6,
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
          "id": 7,
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
          "id": 8,
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
          "id": 9,
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
      ],
      "pagination": {
        "currentPage": 1,
        "itemsPerPage": 10,
        "totalItems": null,
        "next": "/dummies?name=dummy&page=2",
        "prev": null
      }
    }
    """

  @swagger
  Scenario: Build a dummy operation
    When one get the swagger json
    Then get collection dummy filters should be configured
    And get collection dummy should be configured

