# GetCollection Operation

## Working with Pagination

Create an input which must either implements PaginationQueryInputInterface
or extends our custom class which itself extends this interface
```php
use ApiScout\Attribute\ApiProperty;
use ApiScout\Response\Pagination\QueryInput\PaginationQueryInput;
use ApiScout\Response\Pagination\QueryInput\PaginationQueryInputInterface;

final class BookQueryInput extends PaginationQueryInput // Or implements PaginationQueryInputInterface
{
    public function __construct(
        #[ApiProperty(description: 'The name of the champion')]
        public readonly ?string $name = '',
        public readonly ?string $city = '',
        int $page = 1,
        int $itemsPerPage = 10
    ) {
        parent::__construct($page, $itemsPerPage);
    }
}
```
This will activate the pagination weither you are working with our Paginator or an iterable

```php
use ApiScout\Attribute\GetCollection;
use ArrayObject;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

final class GetPaginatedCollectionBookController
{
    #[GetCollection('/books', resource: Book::class)]
    public function __invoke(
        #[MapQueryString] BookQueryInput $query,
    ): ArrayObject {
```

```php
use ApiScout\Attribute\GetCollection;
use ApiScout\Response\Pagination\Pagination;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

final class GetPaginatedCollectionBookController extends AbstractController
{
    #[GetCollection('/paginated_books', resource: Book::class)]
    public function __invoke(
        #[MapQueryString] BookQueryInput $query,
    ): Pagination {
```

## Working without Pagination

Create an Input which does not extends our
`PaginationQueryInput` or implements our `PaginationQueryInputInterface`

```php
final class BookQueryInput
{
    public function __construct(
        public readonly ?string $name = '',
        public readonly ?string $city = '',
    ) {
    }
}
```




