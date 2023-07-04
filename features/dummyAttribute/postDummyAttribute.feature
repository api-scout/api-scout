Feature: Create a DummyAttribute resource

  @http
  Scenario: Post a resource
    When one post a dummy attribute with:
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
    Then created

  @swagger
  Scenario: Build a dummy attribute operation
    When one get the swagger json
    Then post dummy attribute should be configured
