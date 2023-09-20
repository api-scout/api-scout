Feature: Upload a DummyAttribute File

  @http
  Scenario: Post a resource
    Given a file named "helloWorld.pdf" with:
    """
    Hello World
    """
    When one upload a dummy attribute file "helloWorld.pdf"
    Then created

  @swagger
  Scenario: Build a dummy attribute operation
    When one get the swagger json
    Then upload dummy attribute file should be configured
