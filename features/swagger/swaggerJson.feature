Feature: Swagger Json Documentation

  @http
  Scenario: Get the swagger ui documentation
    When one get the swagger json documentation
    Then success
    And swagger json documentation should be correctly configured
