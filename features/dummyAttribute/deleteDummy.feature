Feature: Delete a DummyAttribute resource

  @http
  Scenario: Delete a resource
    When one delete a dummy with:
    | name | Pink |
    Then no content

  @swagger
  Scenario: Delete a dummy attribute operation
    When one get the swagger json
    Then delete dummy attribute should be configured


