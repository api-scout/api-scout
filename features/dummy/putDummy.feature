Feature: Update with PUT a Dummy resource

  @http
  Scenario: Put a resource
    When one put a dummy with:
    """
    {
      "firstName": "Pink",
      "lastName": "Floyd",
      "age": "25-05-1993",
      "ulid": "01H3VY5WDTNNK2MDBSD23EK0HS",
      "uuid": null,
      "address": {
          "street": "127 avenue of the street",
          "zipCode": "13100",
          "city": "California",
          "country": "US"
      }
    }
    """
    Then success
    And put dummy response should have:
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

  @http
  Scenario: Put a resource with wrong value
    When one put a dummy with:
    """
    {
      "firstName": "",
      "lastName": "Floyd",
      "age": "25-05-1993",
      "ulid": "01H3VY5WDTNNK2MDBSD23EK0HS",
      "uuid": null,
      "address": {
          "street": "127 avenue of the street",
          "zipCode": "13100",
          "city": "California",
          "country": "US"
      }
    }
    """
    Then invalid
    And invalid reason should be 'firstName: This value should not be blank.'

  @swagger
  Scenario: Build a dummy attribute operation
    When one get the swagger json
    Then put dummy should be configured
