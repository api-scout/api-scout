# Post, Put or Patch

## Basic installation

```php
final class UpdateDummyController extends AbstractController
{
    #[Post('/dummies')]
    public function __invoke(
        #[MapRequestPayload] DummyPayloadInput $dummyPayloadInput,
    ): DummyOutput {
```

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
