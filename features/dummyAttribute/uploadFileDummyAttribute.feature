Feature: Upload a DummyAttribute File

  @http
  Scenario: Upload a DummyAttribute File resource
    Given a file named "dummyAttributeFile.pdf" with:
    """
    Dummy Attribute File
    """
    When one upload a dummy attribute file "dummyAttributeFile.pdf"
    Then created

  @swagger
  Scenario: Build a dummy attribute operation
    When one get the swagger json
    Then upload dummy attribute file should be configured
