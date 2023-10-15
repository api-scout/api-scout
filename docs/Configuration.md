# Configuration

```yaml
# config/packages/api_scout.yaml

api_scout:
  # The title of the API.
  title: 'API title'
  # The description of the API.
  description: 'API description'
  # The version of the API.
  version: '0.0.0'
  # Enable Swagger.
  enable_swagger: true
  # Enable Swagger UI.
  enable_swagger_ui: true
  # Enable ReDoc.
  enable_re_doc: true
  # Enable the docs.
  enable_docs: true
  # Specify an asset package name to use.
  asset_package: null
  # The list of exceptions mapped to their HTTP status code.
  exception_to_status:
    Symfony\Component\Validator\Exception\ValidationFailedException: 400
  openapi:
    # The contact information for the exposed API.
    contact:
      # The identifying name of the contact person/organization.
      name:
      # The URL pointing to the contact information. MUST be in the format of a URL.
      url:
      # The email address of the contact person/organization. MUST be in the format of an email address.
      email:
    # A URL to the Terms of Service for the API. MUST be in the format of a URL.
    terms_of_service: null
    # The license information for the exposed API.
    license:
      # The license name used for the API.
      name: null
      # URL to the license used for the API. MUST be in the format of a URL.
      url: null
    # The swagger API keys.
    api_keys: []
  oauth:
    # To enable or disable OAuth.
    enabled: false
    # The OAuth client ID.
    clientId: ''
    # The OAuth client secret.
    clientSecret: ''
    # The OAuth type.
    type: 'oauth2'
    # The OAuth flow grant type.
    flow: 'application'
    # The OAuth token URL. Make sure to check the specification tokenUrl is not needed for an implicit flow.
    tokenUrl: ''
    # The OAuth authentication URL.
    authorizationUrl: ''
    # The OAuth scopes.
    scopes: []
```
