# Migrate from FOSRestBundle

[FOSRestBundle](https://fosrestbundle.readthedocs.io/en/3.x/index.html) is a tool to help you in the job of
creating a REST API with Symfony.
This page provides a guide to help developers migrating from FOSRestBundle to ApiScout.

## Feature comparison

The table below provides a list of the main features you can find in FOSRestBundle, and their equivalents in ApiScout

### Make CRUD Endpoints

#### In FOSRestBundle

```php
class BookController
{
    #[Rest\QueryParam(name: 'page', requirements: '\d+', default: 1, description: 'The collection page number')]
    #[Rest\QueryParam(name: 'sort', requirements: '^(id|name)$', default: 'id', description: 'Books sort by id or name')]
    #[Rest\QueryParam(name: 'order', requirements: '^(asc|desc)$', default: 'id', description: 'Order by ASC / DESC')]
    #[Rest\View(200)]
    #[Route('/books', methods: ['GET'])]
    public function listBooks(ParamFetcher $paramFetcher): Response
    {
        $page = (int) $paramFetcher->get('page') ?: 1;
        $sort = $paramFetcher->get('sort');
        $order = $paramFetcher->get('order');
        
        //********
    }
    
    #[ParamConverter('post', class: Book::class)]
    #[Rest\View(200)]
    #[Route('/book/{id}', methods: ['GET'])]
    public function getBook(Book $book): Response
    {
        //********
    }
    
    #[ParamConverter(
      'book',
      class: BookPayloadInput::class,
      options: ['deserializationContext' => ['allow_extra_attributes' => false]],
      converter: 'fos_rest.request_body'
    )]
    #[Rest\View(201)]
    #[Route('/api/libraries', methods: ['POST'])]
    public function addBook(BookPayloadInput $book): Response
    {
        //********
    }
}
```

#### In ApiScout

```php
class BookController 
{
    #[GetCollection('/books', resource: Book::class)]
    public function listBooks(#[MapQueryString] ?BookQueryInput $query): BookCollectionOutput
    {
        //********
    }
    
    #[Get('/books/{id}', resource: Book::class)]
    public function bookDetail(Book $book): BookDetailOutput
    {
        //********
    }
    
    #[Post('/books', resource: Book::class)]
    public function addBook(
        #[MapRequestPayload(
            serializationContext: [AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false]
        )] AddBookInput $dummyPayloadInput
    ): AddBookOutput {
        //********
    }
}
```

It also works with the serialization groups

#### In FOSResBundle

```php
class BookController
{
    #[View(serializerGroups: ['group1', 'group2'])]
    public function listBooks(): Response
}
```

#### In ApiScout

```php
class BookController 
{ 
    #[GetCollection('/books', resource: Book::class, normalizationContext: ['groups' => ['group1', 'group2']])]
    public function bookDetail(Book $book): Book
}
```

### Security

#### In FOSRestBundle

Use Symfony’s Security component to control your API access.

#### In ApiScout

Works the same way as FOSRestBundle

Note you can also use the security.yml file if you only need to limit access to specific roles.


### API versioning

#### In FOSRestBundle

FOSRestBundle provides a way to provide versions to your APIs in a way users have to specify which one they want to use.

See [API versioning](https://github.com/FriendsOfSymfony/FOSRestBundle/blob/3.x/Resources/doc/versioning.rst).

#### In ApiScout

ApiScout works the same ways as ApiPlatform except with the sunset attribute

