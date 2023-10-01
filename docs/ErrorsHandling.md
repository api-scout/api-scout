# Errors Handling

When using global configuration
```yaml
# config/packages/api_scout.yaml
api_platform:
    # ...
    exception_to_status:
      # The 2 following handlers are registered by default
      Symfony\Component\Serializer\Exception\ExceptionInterface: 400
      Symfony\Component\Validator\Exception\ValidationFailedException: !php/const Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
```

When using the Controller
```php
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class PostDummyController
{
    #[Post(
        '/dummies',
        exceptionToStatus: [ValidationFailedException::class => 415]
    )]
    public function __invoke(
        #[MapRequestPayload] DummyPayloadInput $dummyPayloadInput,
    ): DummyOutput {
```
