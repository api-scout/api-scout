# Get

## Basic installation

```php
use ApiScout\Attribute\Get;

final class GetBookController
{
    #[Get('/books/{id}')]
    public function __invoke(int $id): BookOutput
    {
        // Write your code here
        
        return new BookOutput(
            // Populate your properties
        );
    }
```

## Advanced installation

### Get attribute
You could override those or add more information using the following parameters. <br />

In the case below you could retrieve your uriVariable using the request and not the symfony binding parameters.
```php
use ApiScout\Attribute\ApiProperty;
use ApiScout\Attribute\Get;

final class GetBookController extends AbstractController
{
    #[Get(
        path: '/books/{id}',
        name: 'app_get_book_attribute',
        output: BookAttributeOutput::class,
        resource: BookAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'string'),
        ],
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function getBook():
}
```
