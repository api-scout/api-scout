# Get

## Basic installation

```php
use ApiScout\Attribute\Get;

final class GetDummyController extends AbstractController
{
    #[Get(path: '/dummies/{id}')]
    public function __invoke(
        int $id,
    ): DummyOutput
```

## Advanced installation

### Get attribute
You could override those or add more information using the following parameters. <br />

In the case below you could retrieve your uriVariable using the request and not the symfony binding parameters.
```php
    #[Get(
        path: '/dummies/{id}',
        name: 'app_get_dummy_attribute',
        output: DummyAttributeOutput::class,
        resource: DummyAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'string'),
        ],
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function getDummy():
```
