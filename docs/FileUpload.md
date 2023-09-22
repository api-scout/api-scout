# FileUpload

## Basic installation

```php
final class UploadDummyFileController extends AbstractController
{
    #[Post(
        path: '/upload_file_dummies_attribute',
        name: 'app_upload_file_dummy_attribute',
        resource: DummyAttribute::class,
        openapi: new Model\Operation(
            requestBody: new Model\RequestBody(
                content: new ArrayObject([
                    'multipart/form-data' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'file' => [
                                    'type' => 'string',
                                    'format' => 'binary',
                                ],
                            ],
                        ],
                    ],
                ])
            )
        )
    )]
    public function __invoke(): DummyOutput
    {
    }
```
