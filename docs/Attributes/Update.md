# Post, Put or Patch

## Basic installation

```php
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use ApiScout\Attribute\Post;

final class UpdateBookController extends AbstractController
{
    #[Post('/books')]
    public function __invoke(
        #[MapRequestPayload] BookPayloadInput $bookPayloadInput,
    ): BookOutput {
        // Write your code here

        return new BookOutput(
            // Populate your properties
        );
    }
```

## Advanced installation

### ApiProperty
You can add more information to the doc regarding your `input` or `output` using the `ApiProperty` attribute
```php
final class BookPayloadInput
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
use ApiScout\Attribute\Post;

final class UpdateBookAttributeController extends AbstractController
{
    #[Post(
      path: '/books_attribute',
      name: 'app_post_book_attribute',
      input: BookAttributePayloadInput::class,
      output: BookAttributeOutput::class,
      resource: BookAttribute::class,
      statusCode: 201,
      deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function postBook(): Response
}
```

The `#[Put()]`, `#[Patch()]` attributes work the same way as the `#[Post()]` one
