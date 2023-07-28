# GetCollection

## Basic installation

```php
final class GetCollectionDummyController extends AbstractController
{
    #[GetCollection('/dummies')]
    public function __invoke(
        #[MapQueryString] ?DummyQueryInput $query,
        PaginatorRequestFactoryInterface $paginatorRequestFactory
    ): DummyCollectionOutput {
        // Your code here
        $pinkFloydCollection = $getFromDataSourceOrService;
        
        return new DummyCollectionOutput(
            $pinkFloydCollection,
            $paginatorRequestFactory->getCurrentPage(),
            $paginatorRequestFactory->getItemsPerPage()
        );
    }
```

## Advanced installation

### ApiProperty Attribute
You can add more information to the doc regarding your `input` or `output` using the `ApiProperty` attribute
```php
final class DummyQueryInput
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

### GetCollection attribute
You could override those or add more information using the following parameters

```php
    #[GetCollection(
        path: '/dummies',
        name: 'app_get_dummy_collection',
        resource: Dummy::class,
        filters: [
            new ApiProperty(name: 'name', type: 'string', required: false, description: 'The name of the champion'),
            new ApiProperty(name: 'page', type: 'integer', required: true, description: 'The page my mate'),
        ],
        input: YourInput::class,
        output: YourOutput::class,
        statusCode: 200,
        paginationEnable: true, // If you want to enable or disable the pagination
        paginationItemsPerPage: 20, // The number of item you want per pages
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
        // You could also add all the parameters you would need to add from a normal #[Route] attribute
    )]
    public function getDummyAttributeCollection(): Response
    {}
```
If `input` and `output` are specified your parameters and return type will be ignored.

### Pagination

```php
final class DummyCollectionOutput extends Paginator implements PaginatorInterface
{
    public function __construct(iterable $items, int $currentPage, int $itemsPerPage)
    {
        $this->type = DummyOutput::class;

        parent::__construct($items, $currentPage, $itemsPerPage);
    }
}
```

If you want your page to be paginated you need to extends from `Paginator` and implement the `PaginatorInterface` 
