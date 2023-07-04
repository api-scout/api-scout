Feature: Get a DummyAttribute resource

  @http
  Scenario: Get a dummy attribute resource
    When one get a dummy attribute with:
      | id | 1 |
    Then success

  @swagger
  Scenario: Build a dummy attribute operation
    When one get the swagger json
    Then get dummy attribute should be configured




