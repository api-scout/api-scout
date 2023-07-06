# Post, Put or Patch

```php
final class UpdateDummyController extends AbstractController
{
    #[Post(
        path: '/dummies',
        name: 'app_add_dummy',
    )]
    public function __invoke(
        #[MapRequestPayload] DummyPayloadInput $dummyPayloadInput,
    ): DummyOutput {
```
The following parameters are mandatory:

- `path` is the path of your route
- `name` is the unique name your route need to have

Your DummyPayloadInput and DummyOutput will automatically be mapped. <br />

## Advanced installation

You could override those or add more information using the following parameters

```php
    #[Post(
        path: '/dummies_attribute',
        name: 'app_post_dummy_attribute',
        input: DummyAttributePayloadInput::class,
        output: DummyAttributeOutput::class,
        tag: DummyAttribute::class,
        statusCode: 201,
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function postDummy(): Response
```

The `#[Put()]`, `#[Patch()]` attributes work the same way as the `#[Post()]` one
