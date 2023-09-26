# Post, Put or Patch

## Basic installation

```php
use ApiScout\Attribute\Post;

final class UpdateDummyController extends AbstractController
{
    #[Post('/dummies')]
    public function __invoke(
        #[MapRequestPayload] DummyPayloadInput $dummyPayloadInput,
    ): DummyOutput {
```

## Advanced installation

### ApiProperty
You can add more information to the doc regarding your `input` or `output` using the `ApiProperty` attribute
```php
use ApiScout\Attribute\ApiProperty;

final class DummyPayloadInput
{
    public function __construct(
        #[ApiProperty(description: 'Our firstname hero')]
        public readonly string $firstName,
        #[ApiProperty(description: 'input lastname', deprecated: true)]
        public readonly ?string $lastName,
    ) {
    }
}
```

### Post attribute
You could override those or add more information using the following parameters

```php
    #[Post(
        path: '/dummies_attribute',
        name: 'app_post_dummy_attribute',
        input: DummyAttributePayloadInput::class,
        output: DummyAttributeOutput::class,
        resource: DummyAttribute::class,
        statusCode: 201,
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function postDummy(): Response
```

The `#[Put()]`, `#[Patch()]` attributes work the same way as the `#[Post()]` one
