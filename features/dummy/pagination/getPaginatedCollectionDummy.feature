Feature: Get Dummy Collection resource

  @http
  Scenario: Get a dummy collection resource
    When one get a paginated dummy collection with "marvin" at page 1
    Then success
    And get paginated dummy collection response should be:
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
        "totalItems": 31,
        "next": "/paginated_dummies?name=marvin&page=2",
        "prev": null
      }
    }
    """

  @http
  Scenario: Get a dummy collection resource
    When one get a paginated dummy collection with "marvin" at page 4
    Then success
    And get paginated dummy collection response should be:
    """
    {
      "data": [
        {
          "id": 30,
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
        "currentPage": 4,
        "itemsPerPage": 10,
        "totalItems": 31,
        "next": null,
        "prev": "/paginated_dummies?name=marvin&page=3"
      }
    }
    """

  @http
  Scenario: Get a dummy collection resource
    When one get a paginated dummy collection with "marvin" at page 5
    Then success
    And get paginated dummy collection response should be:
    """
    {
      "data": [],
      "pagination": {
        "currentPage": 5,
        "itemsPerPage": 10,
        "totalItems": 31,
        "next": null,
        "prev": "/paginated_dummies?name=marvin&page=4"
      }
    }
    """

  @swagger
  Scenario: Build a dummy operation
    When one get the swagger json
    Then get paginated collection dummy filters should be configured
    And get paginated collection dummy should be configured

