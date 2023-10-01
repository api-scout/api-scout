# ApiScout 🤠


<p align="center">
  <img src="docs/Images/logo-sponsor.svg" width="250" title="alximy logo">
</p>

This library is sponsored by [Alximy](https://alximy.io)

>A team of passionate humans from very different backgrounds, sharing our love of PHP, Symfony and its ecosystem. Our CTO, Expert developers, tech leads, can help you learn or develop the tools you need, and perform audits or tailored workshops. We value contributing to the Open Source community and are willing to mentor new contributors in our team or yours.


## Introduction

The purpose of this repo is to provide a bundle which will
auto document your api using attribute in your controller

## Installation

```bash
composer require api-scout/api-scout dev-beta
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
# config/routes/api_scout.yaml

api_scout:
  resource: '.'
  type: api_scout
```

#### Enable Swagger routes
- [OpenApi](docs/OpenApi.md)

#### Operations
- [GetCollection](docs/Attributes/GetCollection.md)
- [Get](docs/Attributes/Get.md)
- [Post, Put, Patch](docs/Attributes/Update.md)
- [Delete](docs/Attributes/Delete.md)

#### Advanced
- [FileUpload](docs/FileUpload.md)
- [ErrorsHandling](docs/ErrorsHandling.md)
- [Configuration](docs/Configuration.md)

#### Migrate to Api-Scout

- [Migrate from ApiPlatform](docs/MigrateToApiScout/ApiPlatform.md)
- [Migrate from FosRestBundle](docs/MigrateToApiScout/FosRestBundle.md)

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
For more information see the [contributing guide](CONTRIBUTING.md)

## Disclaimer

This package is still in early development and subject to changes without backward compatibility. <br />
Consequently we are not responsible if you decide to use it and some breaking changes occur. <br />
Thanks for your understanding
