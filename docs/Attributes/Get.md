# Get

```php
final class GetDummyController extends AbstractController
{
    #[Get(
        path: '/dummies/{id}',
        name: 'app_get_dummy',
        class: Dummy::class
    )]
    public function __invoke(
        int $id,
    ): DummyOutput
```
The following parameters are mandatory:

- `path` is the path of your route
- `name` is the unique name your route need to have
- `class` can also be a string and non class-string, this is the section of your api documentation

Your query parameters and DummyOutput will automatically be mapped. <br />

But you could override those or add more information using the following parameters. <br />

In the case below you could retrieve your uriVariable using the request and not the symfony binding parameters.
```php
    #[Get(
        path: '/dummies/{id}',
        name: 'app_get_dummy_attribute',
        output: DummyAttributeOutput::class,
        class: DummyAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'string'),
        ],
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function getDummy():
```
