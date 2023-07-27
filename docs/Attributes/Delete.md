# Delete

## Basic installation

```php
final class DeleteDummyController extends AbstractController
{
    #[Delete('/dummies/{name}')]
    public function __invoke(
        string $name
    ): JsonResponse {
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
```

## Advanced installation

You could specify more attribute in case you would need to customize your response or if your parameters are retrieved using the request
```php
    #[Delete(
        path: '/dummies_attribute/{id}',
        name: 'app_delete_dummy_attribute',
        tag: DummyAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'int'),
        ],
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function deleteDummyAttribute():
```
