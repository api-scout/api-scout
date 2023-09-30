Feature: Create a resource with an empty payload

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
