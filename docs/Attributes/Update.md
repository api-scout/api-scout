# Post, Put or Patch

```php
final class UpdateDummyController extends AbstractController
{
    #[Post(
        path: '/dummies',
        name: 'app_add_dummy',
        class: Dummy::class,
    )]
    public function __invoke(
        #[MapRequestPayload] DummyPayloadInput $dummyPayloadInput,
    ): DummyOutput {
```
The following parameters are mandatory:

- `path` is the path of your route
- `name` is the unique name your route need to have
- `class` can also be a string and non class-string, this is the section of your api documentation

Your DummyPayloadInput and DummyOutput will automatically be mapped. <br />

But you could override those or add more information using the following parameters

```php
    #[Post(
        path: '/dummies_attribute',
        name: 'app_post_dummy_attribute',
        input: DummyAttributePayloadInput::class,
        output: DummyAttributeOutput::class,
        class: DummyAttribute::class,
        statusCode: 201,
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function postDummy(): Response
```

The `#[Put()]`, `#[Patch()]` attributes work the same way as the `#[Post()]` one
