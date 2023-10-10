Feature: Create a Dummy resource

  @http
  Scenario: Post a resource
    When one post a dummy entity with:
    """
    {
      "firstName": "Pink",
      "lastName": "Floyd",
      "addressEntity": {
        "name": "alximy",
        "description": "This is the alximy company"
      }
    }
    """
    Then created
    And post dummy entity response should have:
    """
    {
      "data": {
        "firstName": "Pink",
        "lastName": "Floyd",
        "addressEntity": {
          "id": 1,
          "name": "alximy",
          "description": "This is the alximy company"
        }
      }
    }
    """

  @http
  Scenario: Post a resource with a wrong value
    When one post a dummy entity with:
    """
    {
      "firstName": "Pink",
      "lastName": "",
      "addressEntity": {
        "name": "alximy",
        "description": "This is the alximy company"
      }
    }
    """
    Then invalid
    And invalid reason should be 'lastName: This value should not be blank.'

  @http
  Scenario: Post a resource with a non existing attribute
    When one post a dummy entity with:
    """
    {
      "firstName": "Pink",
      "lastName": "Floyd",
      "toto": "",
      "addressEntity": {
        "name": "alximy",
        "description": "This is the alximy company"
      }
    }
    """
    Then invalid
    And invalid reason should be 'toto: Extra attribute: "toto" is not allowed'

  @swagger
  Scenario: Build a dummy entity attribute operation
    When one get the swagger json
    Then post dummy entity should be configured

