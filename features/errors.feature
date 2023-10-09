Feature: Test the Error Customisation

  @http
  Scenario: Successfully trigger the empty payload violation
    When one post an empty payload
    Then invalid
    And  post with empty payload response should have:
    """
    {
      "violations": [
        {
          "path": "payload",
          "message": "Payload should not be empty"
        }
      ]
    }
    """

  @swagger
  Scenario: Controllers tagged as false with openapi should not be configured
    When one get the swagger json
    Then error controllers should not be configured
