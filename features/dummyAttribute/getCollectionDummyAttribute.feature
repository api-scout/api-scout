Feature: Get a DummyAttribute Collection resource

  @http
  Scenario: Get a collection resource
    When one get a dummy attribute collection
    Then success


  @swagger
  Scenario: Build a dummy attribute operation
    When one get the swagger json
#    Then get collection dummy attribute should be configured
