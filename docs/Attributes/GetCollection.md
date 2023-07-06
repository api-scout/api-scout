# GetCollection

```php
final class GetCollectionDummyController extends AbstractController
{
    #[GetCollection(
        path: '/dummies',
        name: 'app_get_dummy_collection',
    )]
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
The following parameters are mandatory:

- `path` is the path of your route
- `name` is the unique name your route need to have

Your DummyQueryInput and DummyCollectionOutput classes will automatically be mapped.

## Advanced installation

You could override those or add more information using the following parameters

```php
    #[GetCollection(
        path: '/dummies',
        name: 'app_get_dummy_collection',
        tag: Dummy::class,
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
