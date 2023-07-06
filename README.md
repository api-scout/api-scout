# ApiScout 🤠

## Introduction

The purpose of this repo is to provide a bundle which will
auto document your api using attribute in your controller

## Installation

```bash
composer require digital-source/api-scout dev-main
```

```php
<?php
# config/bundles.php

return [
    // ...
    ApiScout\Bridge\Symfony\Bundle\ApiScoutBundle::class => ['all' => true]
];
```

```yaml
# config/packages/api_scout.yaml

api_scout:
  path: '%kernel.project_dir%/src/Controller/'
```

```yaml
# config/routes/api_scout.yaml

api_scout:
  resource: '.'
  type: api_scout
```

## Enable Swagger routes

```yaml
# config/routes.yaml

api_doc:
  resource: '@ApiScoutBundle/Resources/config/routes/routes.php'

```

You will need to run `composer require symfony/asset` first if you haven't installed this package
```bash
bin/console assets:install
```

## Usages

#### Operations
- [GetCollection](docs/Attributes/GetCollection.md)
- [Get](docs/Attributes/Get.md)
- [Post, Put, Patch](docs/Attributes/Update.md)
- [Delete](docs/Attributes/Delete.md)

#### Advanced
- [Configuration](docs/Configuration.md)

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
