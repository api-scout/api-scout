Feature: Get Dummy Collection resource

  @http
  Scenario: Get a dummy collection resource
    When one get a doctrine paginated dummy collection with "marvin" at page 1
    Then success
    And get paginated dummy collection response should be:
    """
    {
      "data": [
        {
          "id": 1,
          "firstName": "Norris",
          "lastName": "Chuck"
        },
        {
          "id": 2,
          "firstName": "Marjory",
          "lastName": "Gaillot"
        },
        {
          "id": 3,
          "firstName": "Marvin",
          "lastName": "Courcier"
        },
        {
          "id": 4,
          "firstName": "Carl",
          "lastName": "Hudson"
        },
        {
          "id": 5,
          "firstName": "Peter",
          "lastName": "Parker"
        },
        {
          "id": 6,
          "firstName": "Edmond",
          "lastName": "Miles"
        },
        {
          "id": 7,
          "firstName": "Ben",
          "lastName": "Affleck"
        },
        {
          "id": 8,
          "firstName": "Edgard",
          "lastName": "Conley"
        },
        {
          "id": 9,
          "firstName": "Lucky",
          "lastName": "Luke"
        },
        {
          "id": 10,
          "firstName": "Jean",
          "lastName": "Dujardin"
        }
      ],
      "pagination": {
        "currentPage": 1,
        "itemsPerPage": 10,
        "totalItems": 23,
        "next": "\/doctrine_paginated_dummies?name=marvin&page=2",
        "prev": null
      }
    }
    """

  @swagger
  Scenario: Build a dummy operation
    When one get the swagger json
    Then get doctrine paginated collection dummy filters should be configured
    And get doctrine paginated collection dummy should be configured

