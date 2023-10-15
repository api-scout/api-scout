# OpenApi

>You will first need to run `composer require symfony/asset` first if you haven't installed this package.

```bash
bin/console assets:install
```

Default route of the open api documentations are:

```shell
 --------------------------- -------- -------- ------ -------------------------
  Name                        Method   Scheme   Host   Path
 --------------------------- -------- -------- ------ -------------------------
  api_scout_swagger_ui       GET      ANY      ANY    /api/docs.{_format}
```

## Changing the Location of Swagger UI

You may want to change its route and/or disable it at the API location.


### Changing the Route 

Manually register the Swagger UI controller:
```yaml
# config/routes.yaml
api_scout_swagger_ui:
  path: /api/docs.{_format}
  controller: api_scout.swagger_ui.action
  methods: GET
  format: html
```

## Disabling Swagger UI or ReDoc
To disable Swagger UI (ReDoc will be shown by default):

```yaml
# config/packages/api_scout.yaml
api_scout:
  # ...
  enable_swagger_ui: false
```

To disable ReDoc:

```yaml
# config/packages/api_scout.yaml
api_scout:
  # ...
  enable_re_doc: false
```
