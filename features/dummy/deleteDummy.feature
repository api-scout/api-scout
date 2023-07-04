Feature: Delete a Dummy resource

  @http
  Scenario: Delete a resource
    When one delete a dummy with:
    | name | Pink |
    Then no content
    And delete dummy response should be empty

  @swagger
  Scenario: Delete a dummy operation
    When one get the swagger json
    Then delete dummy should be configured
