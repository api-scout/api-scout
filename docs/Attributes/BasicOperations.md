# Operations Configuration

ApiScout attributes are extending the Symfony #[Route] attribute, and so your routes configuration will still be working !! <br />

However you'll surely want to also add the `resource` property from the operations
to groups by category your routes in the generated OpenApi documentation.

## Get

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

## Post, Put, Patch

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

The `#[Put()]`, `#[Patch()]` attributes work the same way as the `#[Post()]` one

## Delete

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

---
**NOTE**: Go to [Open Api Specification support](../OpenApi/OpenApiSpecificationSupport.md) for more customizable informations

