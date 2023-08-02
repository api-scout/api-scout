# Migrate from ApiPlatform

[api-platform](https://api-platform.com) is an Api-first framework which is able to automatically
expose entities mapped as “API resources” through a REST API supporting CRUD operations. <br />

This page provides a guide to help developers migrating from ApiPlatform to ApiScout

## Feature comparison

The table below provides a list of the main features you can find in ApiPlatform, and their equivalents in ApiScout

### Make CRUD Endpoints

#### In ApiPlatform

Add the ApiResource attribute to your entities, and enable operations you desire inside. By default, every operations are activated.

See [Operations](https://api-platform.com/docs/core/operations/).

```php
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/books',
            security: "is_granted('ROLE_WRITER')",
            name: 'list_books',
            provider: ListBooksProvider::class
        ),
        new Get(
            uriTemplate: 'books/{id}',
            output: BookDetailOutput::class,
            name: 'book_detail',
            provider: BookDetailProvider::class,
        ),
        new Post(
            uriTemplate: '/books',
            processor: AddBookProcessor::class,
            input: AddBookInput::class,
            output: AddBookOutput::class,
        ),
        new Patch(
            // ****
        ),
        new Put(
            // ****
        ),
        new Delete(
            // ****
        ),
    ]
)]
class Book
{}
```

#### In ApiScout

```php
class BookController 
{
    #[IsGranted('ROLE_WRITER')]
    #[GetCollection('/books', name: 'list_books', resource: Book::class)]
    public function listBooks(#[MapQueryString] ?BookQueryInput $query): BookCollectionOutput
    {
        //********
    }
    
    #[Get('/books/{id}', name: 'book_detail', resource: Book::class)]
    public function bookDetail(Book $book): BookDetailOutput
    {
        //********
    }
    
    #[Post('/books', resource: Book::class)]
    public function addBook(#[MapRequestPayload] AddBookInput $dummyPayloadInput): AddBookOutput
    {
        //********
    }
    
    #[Patch('/books')]
    public function ....

    #[Put('/books')]
    public function ....

    #[Delete('/books')]
    public function ....
}
```

It also works with the serialization groups

#### In ApiPlatform

```php
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Book
{
    #[Groups(['read', 'write'])]
    public $name;
    #[Groups('write')]
    public $author;
    // ...
}
```

#### In ApiScout

```php
class BookController 
{ 
    #[Get('/books/{id}', resource: Book::class, normalizationContext: ['groups' => ['read']])]
    public function bookDetail(Book $book): Book
    
    #[Post('/books', resource: Book::class, normalizationContext: ['groups' => ['read']])]
    public function addBook(
        #[MapRequestPayload(serializationContext: ['groups' => ['write']])] Book $dummyPayloadInput,
    ): Book
}
```

### Security

#### In API Platform

Use the security attribute in the ApiResource and ApiProperty attributes.
It is an Expression language string describing who can access your resources or who can see the properties of your resources.
By default, everything is accessible without authentication.

#### In ApiScout

Use Symfony’s Security component to control your API access.

Note you can also use the security.yml file if you only need to limit access to specific roles.


### API versioning

#### In API Platform

API Platform has no native support to API versioning, but instead provides an approach consisting of deprecating resources when needed.
It allows a smoother upgrade for clients, as they need to change their code only when it is necessary.

See [Deprecating Resources and Properties](https://api-platform.com/docs/core/deprecations/).

#### In ApiScout

ApiScout works the same ways as ApiPlatform except with the sunset attribute
