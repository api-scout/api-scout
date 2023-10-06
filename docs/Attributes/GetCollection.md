# GetCollection

## Working with Collection

#### Paginated collection
```php
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use ApiScout\Attribute\GetCollection;

final class GetCollectionBookController
{
    #[GetCollection('/books')]
    public function __invoke(
        #[MapQueryString] ?BookQueryInput $query,
    ): \ArrayObject {
        // Your code here
        return new \ArrayObject($myCollection);
    }
```

#### Deactivated pagination for collection
```php
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use ApiScout\Attribute\GetCollection;

final class GetCollectionBookController
{
    #[GetCollection('/books', paginationEnabled: false)]
    public function __invoke(
        #[MapQueryString] ?BookQueryInput $query,
    ): \ArrayObject {
        // Your code here
        return new \ArrayObject($myCollection);
    }
```

## Working with pagination

```php
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use ApiScout\Response\Pagination\Pagination;
use ApiScout\Attribute\GetCollection;

final class GetPaginatedCollectionBookController
{
    #[GetCollection('/books')]
    public function __invoke(
        #[MapQueryString] ?BookQueryInput $query,
    ): Pagination {
        // Your code here
        
        return new Pagination(
            $myCollection,
            1,
            10,
            count($myCollection)
        );
    }
```

## Advanced installation

### ApiProperty Attribute
You can add more information to the doc regarding your `input` or `output` using the `ApiProperty` attribute
```php
final class BookQueryInput
{
    public function __construct(
        #[ApiProperty(description: 'The name of the champion')]
        public readonly ?string $name = '',
        public readonly ?string $city = '',
        #[ApiProperty(name: 'page', type: 'integer', required: true, description: 'The page my mate')]
        public readonly int $page = 1,
    ) {
    }
}
```

### Working only with attribute
You could override those or add more information using the following parameters

```php
    #[GetCollection(
        path: '/books',
        name: 'app_get_book_collection',
        resource: Book::class,
        filters: [
            new ApiProperty(name: 'name', type: 'string', required: false, description: 'The name of the champion'),
            new ApiProperty(name: 'page', type: 'integer', required: true, description: 'The page my mate'),
        ],
        input: BookInput::class,
        output: BookOutput::class,
        statusCode: 200,
        paginationEnable: true, // If you want to enable or disable the pagination
        paginationItemsPerPage: 20, // The number of item you want per pages
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
        // You could also add all the parameters you would need to add from a normal #[Route] attribute
    )]
    public function getBookAttributeCollection(): Response
    {}
```
If `input` and `output` are specified your parameters and return type will be ignored.
