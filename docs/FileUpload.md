# Handling File Upload

## Basic installation

```php
final class UploadDummyFileController extends AbstractController
{
    #[Post(
        path: '/upload_file_dummies_attribute',
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
    public function __invoke(Request $request): UploadDummyFileOutput
    {
        $uploadedFile = $request->files->get('file');
        
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" not found');
        }
        
        // Do what your domain needs you to do here
    }
```
