# Delete

## Basic installation

```php
use ApiScout\Attribute\Delete;

final class DeleteBookController extends AbstractController
{
    #[Delete('/books/{name}')]
    public function __invoke(
        string $name
    ): JsonResponse {
        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
```

## Advanced installation

### Delete attribute
You could specify more attribute in case you would need to customize your response or if your parameters are retrieved using the request
```php
use ApiScout\Attribute\ApiProperty;
use ApiScout\Attribute\Delete;

final class DeleteBookAttributeController extends AbstractController
{
    #[Delete(
        path: '/books_attribute/{id}',
        name: 'app_delete_book_attribute',
        resource: BookAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'int'),
        ],
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function deleteBookAttribute():
}
```
