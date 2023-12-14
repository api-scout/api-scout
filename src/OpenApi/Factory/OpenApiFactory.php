<?php

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiScout\OpenApi\Factory;

use ApiScout\Attribute\CollectionOperationInterface;
use ApiScout\OpenApi\Http\Abstract\HttpRequest;
use ApiScout\OpenApi\Http\Abstract\HttpResponse;
use ApiScout\OpenApi\JsonSchema\Factory\FilterFactory;
use ApiScout\OpenApi\JsonSchema\Factory\FilterFactoryInterface;
use ApiScout\OpenApi\JsonSchema\Factory\SchemaFactoryInterface;
use ApiScout\OpenApi\JsonSchema\JsonSchema;
use ApiScout\OpenApi\Model;
use ApiScout\OpenApi\OpenApi;
use ApiScout\OpenApi\Options;
use ApiScout\OpenApi\SchemaRefNameGenerator;
use ApiScout\OpenApi\Trait\ClassNameNormalizerTrait;
use ApiScout\Operation;
use ApiScout\Operations;
use ApiScout\Resource\OperationProviderInterface;
use ArrayObject;
use LogicException;
use Throwable;

use function in_array;
use function is_object;

/**
 * Generates an Open API v3 specification.
 *
 * Inspired by ApiPlatform\OpenApi\Factory\OpenApiFactory
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class OpenApiFactory implements OpenApiFactoryInterface
{
    use ClassNameNormalizerTrait;
    use ClassNameNormalizerTrait;
    public const BASE_URL = 'base_url';

    private readonly Options $openApiOptions;

    /**
     * @param array<class-string<Throwable>, int> $exceptionsToStatuses
     */
    public function __construct(
        private readonly OperationProviderInterface $resourceCollection,
        private readonly SchemaFactoryInterface $schemaFactory,
        private readonly FilterFactoryInterface $filterFactory,
        private readonly array $exceptionsToStatuses,
        ?Options $openApiOptions = null,
    ) {
        $this->openApiOptions = $openApiOptions ?: new Options('OpenApi Documentation');
    }

    public function __invoke(array $context = []): OpenApi
    {
        $collections = $this->resourceCollection->getCollection();

        $baseUrl = $context[self::BASE_URL] ?? '/';
        $contact = null === $this->openApiOptions->getContactUrl() || null === $this->openApiOptions->getContactEmail() ? null : new Model\Contact($this->openApiOptions->getContactName(), $this->openApiOptions->getContactUrl(), $this->openApiOptions->getContactEmail());
        $license = null === $this->openApiOptions->getLicenseName() ? null : new Model\License($this->openApiOptions->getLicenseName(), $this->openApiOptions->getLicenseUrl());
        $info = new Model\Info($this->openApiOptions->getTitle(), $this->openApiOptions->getVersion(), trim($this->openApiOptions->getDescription()), $this->openApiOptions->getTermsOfService(), $contact, $license);
        $servers = '/' === $baseUrl || '' === $baseUrl ? [new Model\Server('/')] : [new Model\Server($baseUrl)];
        $paths = new Model\Paths();
        $schemas = new ArrayObject();

        $this->collectPaths(
            $collections,
            $paths,
            $schemas,
        );

        $securitySchemes = $this->getSecuritySchemes();
        $securityRequirements = [];

        foreach (array_keys($securitySchemes) as $key) {
            $securityRequirements[] = [$key => []];
        }

        return new OpenApi(
            $info,
            $servers,
            $paths,
            new Model\Components(
                $schemas,
                new ArrayObject(),
                new ArrayObject(),
                new ArrayObject(),
                new ArrayObject(),
                new ArrayObject(),
                new ArrayObject($securitySchemes),
            ),
            $securityRequirements,
        );
    }

    private function collectPaths(
        Operations $resource,
        Model\Paths $paths,
        ArrayObject $schemas,
    ): void {
        foreach ($resource->getOperations() as $operationName => $operation) {
            $openapiOperation = $operation->getOpenapi();

            // Operation ignored from OpenApi
            if (false === $openapiOperation) {
                continue;
            }

            $resourceShortName = $this->normalizeClassName($operation->getResource());
            $method = $operation->getMethod();

            if (!in_array($method, Model\PathItem::$methods, true)) {
                continue;
            }

            if (!is_object($openapiOperation)) {
                $openapiOperation = new Model\Operation();
            }

            // Complete with defaults
            $openapiOperation = new Model\Operation(
                operationId: null !== $openapiOperation->getOperationId() ? $openapiOperation->getOperationId() : $this->normalizeOperationName($operationName),
                tags: null !== $openapiOperation->getTags() ? $openapiOperation->getTags() : [$resourceShortName],
                responses: null !== $openapiOperation->getResponses() ? $openapiOperation->getResponses() : [],
                summary: null !== $openapiOperation->getSummary() ? $openapiOperation->getSummary() : $this->getPathDescription($resourceShortName, $method, $operation instanceof CollectionOperationInterface),
                description: null !== $openapiOperation->getDescription() ? $openapiOperation->getDescription() : $this->getPathDescription($resourceShortName, $method, $operation instanceof CollectionOperationInterface),
                externalDocs: $openapiOperation->getExternalDocs(),
                parameters: null !== $openapiOperation->getParameters() ? $openapiOperation->getParameters() : [],
                requestBody: $openapiOperation->getRequestBody(),
                callbacks: $openapiOperation->getCallbacks(),
                deprecated: null !== $openapiOperation->getDeprecated() ? $openapiOperation->getDeprecated() : (bool) $operation->getDeprecationReason(),
                security: null !== $openapiOperation->getSecurity() ? $openapiOperation->getSecurity() : null,
                servers: null !== $openapiOperation->getServers() ? $openapiOperation->getServers() : null,
                extensionProperties: $openapiOperation->getExtensionProperties(),
            );

            $path = $operation->getPath();
            $pathItem = $paths->getPath($path) ?: new Model\PathItem();

            $schema = new JsonSchema('openapi');
            $schema->setDefinitions($schemas);

            if ([] !== $operation->getUriVariables()) {
                $openapiOperation = $this->filterFactory->buildUriParams(
                    FilterFactory::PATH,
                    $operation->getUriVariables(),
                    $openapiOperation,
                );
            }

            if ($operation instanceof CollectionOperationInterface) {
                $openapiOperation = $this->filterFactory->buildUriParams(
                    FilterFactory::QUERY,
                    $operation->getFilters(),
                    $openapiOperation,
                );
            }

            $openapiOperation = $this->buildOpenApiResponse($openapiOperation, $operation, $schemas);

            if (null === $openapiOperation->getRequestBody()
                && null !== $operation->getInput()
                && in_array(
                    $method,
                    [HttpRequest::METHOD_PATCH, HttpRequest::METHOD_PUT, HttpRequest::METHOD_POST],
                    true,
                )) {
                $operationInputSchema = $this->schemaFactory->buildSchema(
                    /** @phpstan-ignore-next-line up to this point if input is set then it has a class-string */
                    $operation->getInput(),
                    $operation->getResource(),
                    $operation->getDenormalizationContext(),
                );
                $this->appendSchemaDefinitions($schemas, $operationInputSchema);

                $openapiOperation = $openapiOperation->withRequestBody(
                    new Model\RequestBody(
                        sprintf('The %s %s resource', HttpRequest::METHOD_POST === $method ? 'new' : 'updated', $resourceShortName),
                        $this->buildContent(
                            $operation,
                        ),
                        true,
                    ),
                );
            }

            /** @phpstan-ignore-next-line */
            $paths->addPath($path, $pathItem->{'with'.ucfirst($method)}($openapiOperation));
        }
    }

    private function buildOpenApiResponse(
        Model\Operation $openapiOperation,
        Operation $operation,
        ArrayObject $schemas,
    ): Model\Operation {
        $existingResponses = $openapiOperation->getResponses() ?: [];
        $resourceShortName = $this->normalizeClassName($operation->getResource());

        // Create responses
        switch ($operation->getMethod()) {
            case HttpRequest::METHOD_GET:
                $openapiOperation = $this->buildResponseContent(
                    $operation->getStatusCode() ?: HttpResponse::HTTP_OK,
                    sprintf(
                        '%s %s',
                        $resourceShortName,
                        $operation instanceof CollectionOperationInterface ? 'collection' : 'resource',
                    ),
                    $operation,
                    $openapiOperation,
                );
                break;
            case HttpRequest::METHOD_POST:
                $openapiOperation = $this->buildResponseContent(
                    $operation->getStatusCode(),
                    sprintf('%s resource created', $resourceShortName),
                    $operation,
                    $openapiOperation,
                );

                $openapiOperation = $this->buildResponseContent(
                    HttpResponse::HTTP_BAD_REQUEST,
                    'Invalid input',
                    null,
                    $openapiOperation,
                );

                break;
            case HttpRequest::METHOD_PATCH:
            case HttpRequest::METHOD_PUT:
                $openapiOperation = $this->buildResponseContent(
                    $operation->getStatusCode(),
                    sprintf('%s resource updated', $resourceShortName),
                    $operation,
                    $openapiOperation,
                );

                $openapiOperation = $this->buildResponseContent(
                    HttpResponse::HTTP_BAD_REQUEST,
                    'Invalid input',
                    null,
                    $openapiOperation,
                );
                break;
            case HttpRequest::METHOD_DELETE:
                $openapiOperation = $this->buildResponseContent(
                    $operation->getStatusCode(),
                    sprintf('%s resource deleted', $resourceShortName),
                    $operation,
                    $openapiOperation,
                );

                break;
        }

        $openapiOperation = $this->buildCustomOpenApiResponse(
            $openapiOperation,
            $operation,
        );

        if (null !== $operation->getOutput() && class_exists($operation->getOutput())) {
            $operationOutputSchema = $this->schemaFactory->buildSchema(
                $operation->getOutput(),
                $operation->getResource(),
                $operation->getNormalizationContext(),
            );

            $this->appendSchemaDefinitions($schemas, $operationOutputSchema);
        }

        if (!$operation instanceof CollectionOperationInterface && HttpRequest::METHOD_POST !== $operation->getMethod()) {
            if (!isset($existingResponses[404])) {
                $openapiOperation = $openapiOperation->withResponse(
                    404,
                    new Model\Response('Resource not found'),
                );
            }
        }

        if (null === $openapiOperation->getResponses()) {
            $openapiOperation = $openapiOperation->withResponse(
                'default',
                new Model\Response('Unexpected error'),
            );
        }

        return $openapiOperation;
    }

    private function buildCustomOpenApiResponse(
        Model\Operation $openapiOperation,
        Operation $operation,
    ): Model\Operation {
        $exceptionsToStatuses = $operation->formatExceptionToStatusWithConfiguration($this->exceptionsToStatuses);

        foreach ($exceptionsToStatuses as $exception => $status) {
            if (!isset($openapiOperation->getResponses()[$status])) {
                if ($status >= 200 && $status < 400 && $this->isReadOperation($operation)) {
                    $openapiOperation = $this->buildResponseContent(
                        $status,
                        $this->exceptionClassnameToMessageError($exception),
                        null,
                        $openapiOperation,
                    );
                }
                if ($status >= 200 && $status < 500 && $this->isWriteOperation($operation)) {
                    $openapiOperation = $this->buildResponseContent(
                        $status,
                        $this->exceptionClassnameToMessageError($exception),
                        null,
                        $openapiOperation,
                    );
                }
            }
        }

        return $openapiOperation;
    }

    private function isReadOperation(Operation $operation): bool
    {
        return HttpRequest::METHOD_GET === $operation->getMethod();
    }

    private function isWriteOperation(Operation $operation): bool
    {
        return HttpRequest::METHOD_POST === $operation->getMethod()
            || HttpRequest::METHOD_PUT === $operation->getMethod()
            || HttpRequest::METHOD_PATCH === $operation->getMethod()
            || HttpRequest::METHOD_DELETE === $operation->getMethod();
    }

    /**
     * @param class-string $class
     */
    private function exceptionClassnameToMessageError(string $class): string
    {
        $explodedClassName = explode('\\', $class);
        $classname = end($explodedClassName);
        $messageErrorException = str_replace('Exception', '', $classname);

        $formatedMessageErrorException = preg_replace('/[A-Z]/', ' $0', $messageErrorException);

        if (null === $formatedMessageErrorException) {
            throw new LogicException('Misformated error message exception.');
        }

        return ltrim($formatedMessageErrorException);
    }

    private function buildResponseContent(
        int $status,
        string $description,
        ?Operation $operation,
        Model\Operation $openapiOperation,
    ): Model\Operation {
        //        if ($operation) {
        //            $responseLinks = $this->getLinks($operations, $operation);
        //        }

        return $openapiOperation->withResponse(
            $status,
            new Model\Response(
                $description,
                null !== $operation ? $this->buildResponseSchema($operation) : null,
                null,
                //                $responseLinks
            ),
        );
    }

    /**
     * @return ArrayObject<Model\MediaType>
     */
    private function buildContent(Operation $operation): ArrayObject
    {
        [$requestMimeTypes, $responseMimeTypes] = $this->getMimeTypes($operation);

        /** @var ArrayObject<Model\MediaType> $content */
        $content = new ArrayObject();
        foreach ($requestMimeTypes as $mimeType => $type) {
            if (null === $operation->getInput()) {
                continue;
            }

            $content[$mimeType] = new Model\MediaType(
                new ArrayObject(
                    [
                        '$ref' => '#/components/schemas/'.SchemaRefNameGenerator::generate(
                            $operation->getResource(),
                            $operation->getInput(),
                            $operation->getDenormalizationContext(),
                        ),
                    ],
                ),
            );
        }

        return $content;
    }

    private function buildResponseSchema(Operation $operation): ArrayObject
    {
        [$requestMimeTypes, $responseMimeTypes] = $this->getMimeTypes($operation);

        /** @var ArrayObject<Model\MediaType> $content */
        $content = new ArrayObject();
        foreach ($responseMimeTypes as $mimeType => $type) {
            if (null === $operation->getOutput()) {
                continue;
            }

            $content[$mimeType] = new Model\MediaType(
                new ArrayObject(
                    [
                        '$ref' => '#/components/schemas/'.SchemaRefNameGenerator::generate(
                            $operation->getResource(),
                            $operation->getOutput(),
                            $operation->getNormalizationContext(),
                        ),
                    ],
                ),
            );
        }

        return $content;
    }

    private function appendSchemaDefinitions(ArrayObject $schemas, ArrayObject $definitions): void
    {
        foreach ($definitions as $key => $value) {
            $schemas[$key] = $value;
        }
    }

    private function getSecuritySchemes(): array
    {
        $securitySchemes = [];

        if ($this->openApiOptions->getOAuthEnabled()) {
            $securitySchemes['oauth'] = $this->getOauthSecurityScheme();
        }

        foreach ($this->openApiOptions->getApiKeys() as $key => $apiKey) {
            $description = sprintf('Value for the %s %s parameter.', $apiKey['name'], $apiKey['type']);
            $securitySchemes[$key] = new Model\SecurityScheme('apiKey', $description, $apiKey['name'], $apiKey['type']);
        }

        return $securitySchemes;
    }

    /**
     * Gets the path for an operation.
     *
     * If the path ends with the optional _format parameter, it is removed
     * as optional path parameters are not yet supported.
     *
     * @see https://github.com/OAI/OpenAPI-Specification/issues/93
     */
    private function getPath(string $path): string
    {
        // Handle either API Platform's URI Template (rfc6570) or Symfony's route
        if (str_ends_with($path, '{._format}') || str_ends_with($path, '.{_format}')) {
            $path = substr($path, 0, -10);
        }

        return str_starts_with($path, '/') ? $path : '/'.$path;
    }

    private function getPathDescription(string $resourceShortName, string $method, bool $isCollection): string
    {
        switch ($method) {
            case HttpRequest::METHOD_GET:
                $pathSummary = $isCollection ? 'Retrieves the collection of %s resources.' : 'Retrieves a %s resource.';
                break;
            case HttpRequest::METHOD_POST:
                $pathSummary = 'Creates a %s resource.';
                break;
            case HttpRequest::METHOD_PATCH:
                $pathSummary = 'Updates the %s resource.';
                break;
            case HttpRequest::METHOD_PUT:
                $pathSummary = 'Replaces the %s resource.';
                break;
            case HttpRequest::METHOD_DELETE:
                $pathSummary = 'Removes the %s resource.';
                break;
            default:
                return $resourceShortName;
        }

        return sprintf($pathSummary, $resourceShortName);
    }

    private function getOauthSecurityScheme(): Model\SecurityScheme
    {
        $oauthFlow = new Model\OAuthFlow(
            $this->openApiOptions->getOAuthAuthorizationUrl(),
            $this->openApiOptions->getOAuthTokenUrl() ?: null,
            $this->openApiOptions->getOAuthRefreshUrl() ?: null,
            new ArrayObject($this->openApiOptions->getOAuthScopes()),
        );

        $description = sprintf(
            'OAuth 2.0 %s Grant',
            strtolower(
                preg_replace(
                    '/[A-Z]/',
                    ' \\0',
                    lcfirst($this->openApiOptions->getOAuthFlow() ?? ''),
                ) ?? '',
            ),
        );
        $implicit = $password = $clientCredentials = $authorizationCode = null;

        switch ($this->openApiOptions->getOAuthFlow()) {
            case 'implicit':
                $implicit = $oauthFlow;
                break;
            case 'password':
                $password = $oauthFlow;
                break;
            case 'application':
            case 'clientCredentials':
                $clientCredentials = $oauthFlow;
                break;
            case 'accessCode':
            case 'authorizationCode':
                $authorizationCode = $oauthFlow;
                break;
            default:
                throw new LogicException('OAuth flow must be one of: implicit, password, clientCredentials, authorizationCode');
        }

        return new Model\SecurityScheme(
            type: $this->openApiOptions->getOAuthType(),
            description: $description,
            flows: new Model\OAuthFlows($implicit, $password, $clientCredentials, $authorizationCode),
        );
    }

    private function getMimeTypes(Operation $operation): array
    {
        $requestFormats = $operation->getInputFormats() ?: [];
        $responseFormats = $operation->getOutputFormats() ?: [];

        $requestMimeTypes = $this->flattenMimeTypes($requestFormats);
        $responseMimeTypes = $this->flattenMimeTypes($responseFormats);

        return [$requestMimeTypes, $responseMimeTypes];
    }

    private function flattenMimeTypes(array $responseFormats): array
    {
        $responseMimeTypes = [];
        foreach ($responseFormats as $responseFormat => $mimeTypes) {
            foreach ($mimeTypes as $mimeType) {
                $responseMimeTypes[$mimeType] = $responseFormat;
            }
        }

        return $responseMimeTypes;
    }

    private function normalizeOperationName(string $operationName): string
    {
        $normalizedOperationName = preg_replace(
            '/^_/',
            '',
            str_replace(['/', '{._format}', '{', '}'], ['', '', '_', ''], $operationName),
        );

        if (null === $normalizedOperationName) {
            throw new LogicException(sprintf('Could not normalize "%s" into operation name.', $operationName));
        }

        return $normalizedOperationName;
    }
}
