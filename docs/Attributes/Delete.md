# Delete

```php
final class DeleteDummyController extends AbstractController
{
    #[Delete(
        path: '/dummies/{name}',
        name: 'app_delete_dummy',
        class: Dummy::class
    )]
    public function __invoke(
        string $name
    ): JsonResponse {
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
```
The following parameters are mandatory:

- `path` is the path of your route
- `name` is the unique name your route need to have
- `class` can also be a string and non class-string, this is the section of your api documentation

Your query parameters will automatically be mapped. <br />

You could specify more attribute in case you would need to customize your response or if your parameters are retrieved using the request
```php
    #[Delete(
        path: '/dummies_attribute/{id}',
        name: 'app_delete_dummy_attribute',
        class: DummyAttribute::class,
        uriVariables: ['id' => 'int'],
        deprecationReason: 'Do not use this route anymore', // If you want to deprecate this route
    )]
    public function deleteDummyAttribute():
```
