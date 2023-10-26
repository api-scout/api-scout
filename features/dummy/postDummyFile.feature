Feature: Create a Dummy File resource

  @http
  Scenario: Post a resource with wrong value
    When one post a dummy file with:
    """
    {
      "fileName": ""
    }
    """
    Then unsupported media type
    And invalid reason should be 'fileName: This value should not be blank.'

  @swagger
  Scenario: Build a dummy attribute operation
    When one get the swagger json
    Then post dummy file should be configured

