# OpenApi Specification Support

ApiScout natively support the [OpenAPI](https://www.openapis.org) API specification format.

## Overriding the OpenAPI Specification

Symfony allows to [decorate services](https://symfony.com/doc/current/service_container/service_decoration.html),
here we need to decorate `api_scout.openapi.openapi_factory`

In the following example, we will see how to override the title and the base path URL
of the Swagger documentation and add a custom filter for the GET operation of /books path.

```yaml
# api/config/services.yaml
    App\OpenApi\OpenApiFactory:
        decorates: 'api_scout.openapi.openapi_factory'
        arguments: [ '@App\OpenApi\OpenApiFactory.inner' ]
        autoconfigure: false
```

```php
<?php
namespace App\OpenApi;

use ApiScout\OpenApi\Factory\OpenApiFactoryInterface;
use ApiScout\OpenApi\OpenApi;
use ApiScout\OpenApi\Model;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $decorated)
    {
    }
    
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        $pathItem = $openApi->getPaths()->getPath('/api/books/{id}');
        $operation = $pathItem->getGet();
        $openApi->getPaths()->addPath('/api/books/{id}', $pathItem->withGet(
            $operation->withParameters(array_merge(
                $operation->getParameters(),
                [new Model\Parameter('fields', 'query', 'Fields to remove of the output')]
            ))
        ));
        $openApi = $openApi->withInfo((new Model\Info('New Title', 'v2', 'Description of my custom API'))->withExtensionProperty('info-key', 'Info value'));
        $openApi = $openApi->withExtensionProperty('key', 'Custom x-key value');
        $openApi = $openApi->withExtensionProperty('x-value', 'Custom x-value value');
        
        // To redefine base path URL
        $openApi = $openApi->withServers([new Model\Server('https://foo.bar')]);
        return $openApi;
    }
}
```

## Using the OpenApi attribute

```php
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use ApiScout\Attribute\Post;
use ApiScout\OpenApi\Model;

final class AddBookController
{
    #[Post(
      path: '/books',
      name: 'app_add_book',
      input: BookPayloadInput::class,
      output: BookOutput::class,
      resource: Book::class,
      openapi: new Model\Operation(
            summary: 'Add the Book i want in my personal library.',
            description: 'Add the Book i want in my  library.',
            requestBody: new Model\RequestBody(
                content: new \ArrayObject([
                    'application/json' => [
                        'schema' => [
                            'type' => 'object', 
                            'properties' => [
                                'name' => ['type' => 'string'], 
                                'description' => ['type' => 'string']
                            ]
                        ], 
                        'example' => [
                            'name' => 'Book name', 
                            'description' => 'A resume a of this book'
                        ]
                    ]
                ])
            ),
            // Look into the Operation object to see more about what you can add here
      ),
      statusCode: 201,
    )]
    public function addBook(
        #[MapRequestPayload] BookInput $bookInput
    ): BookOutput
}
```

You can even lie to your documentation
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
    public function getBook(): JsonResponse
}
```

## Using the ApiProperty attribute

You can add more information to the doc regarding your `input` or `output` using the `ApiProperty` attribute
```php
use ApiScout\Attribute\ApiProperty;

final class BookQueryInput
{
    public function __construct(
        #[ApiProperty(description: 'The name of the champion')]
        public readonly ?string $name = '',
        #[ApiProperty(name: 'age', type: 'integer', required: true, description: 'How old is this book ?')]
        public readonly int $age = 80,
    ) {
    }
}
```

## Disabling an Operation from OpenApi Documentation

Sometimes you may want to disable an operation from the OpenAPI documentation to not expose it.
Using the openapi boolean option disables this operation from the OpenAPI documentation:

```php
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use ApiScout\Attribute\Post;
use ApiScout\OpenApi\Model;

final class AddMySecretBookController
{
    #[Post(
      path: '/secret_books',
      name: 'app_add_my_secret_book',
      openapi: false,
    )]
    public function addBook(
        #[MapRequestPayload] BookInput $bookInput
    ): BookOutput
}
```

## Info Object

The [info object](https://swagger.io/specification/#info-object) provides metadata about the API like licensing information or a contact.
You can specify this information using API Platform’s configuration:
```yaml
api_scout:
    # The title of the API.
    title: 'API title'
    # The description of the API.
    description: 'API description'
    # The version of the API.
    version: '0.0.0'
    openapi:
        # The contact information for the exposed API.
        contact:
            # The identifying name of the contact person/organization.
            name:
            # The URL pointing to the contact information. MUST be in the format of a URL.
            url:
            # The email address of the contact person/organization. MUST be in the format of an email address.
            email:
        # A URL to the Terms of Service for the API. MUST be in the format of a URL.
        termsOfService:
        # The license information for the exposed API.
        license:
            # The license name used for the API.
            name:
            # URL to the license used for the API. MUST be in the format of a URL.
            url:
```
