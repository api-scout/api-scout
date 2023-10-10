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

namespace ApiScout\Resource;

use ApiScout\Attribute\ApiProperty;
use ApiScout\Exception\ParamShouldBeTypedException;
use ApiScout\Exception\ResourceClassNotFoundException;
use ApiScout\Operation;
use ApiScout\Operations;
use ApiScout\Response\Pagination\Pagination;
use ApiScout\Response\Pagination\QueryInput\PaginationQueryInputInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePagination;
use LogicException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Contracts\Cache\CacheInterface;

use function array_key_exists;
use function function_exists;
use function is_int;

/**
 * Build the Operations.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class OperationProvider implements OperationProviderInterface
{
    private const CACHE_PREFIX = 'api_scout.operation.';

    public function __construct(
        private readonly DirectoryClassesExtractor $directoryClassExtractor,
        private readonly CacheInterface $cache,
    ) {
    }

    public function getCollection(): Operations
    {
        $classes = $this->directoryClassExtractor->extract();
        $operations = [];

        foreach ($classes as $controller) {
            if (!class_exists($controller)) {
                throw new ResourceClassNotFoundException($controller);
            }

            $reflectionClass = new ReflectionClass($controller);

            foreach ($reflectionClass->getMethods() as $method) {
                if ($method->class !== $controller) {
                    // We only want the method of the current class. This line avoid reading inherited classes
                    continue;
                }

                if ($this->isOperationResource($method)) {
                    $operation = $this->cache->get(
                        self::getOperationCacheKey(self::getControllerName($method)),
                        fn () => $this->buildOperationFromMethod($method, $controller)
                    );

                    if ($operation->getName() === null) {
                        throw new LogicException('Operation name should have been initialized before hand.');
                    }

                    $operations[$operation->getName()] = $operation;
                }
            }
        }

        return new Operations($operations);
    }

    public function get(string $controllerName): ?Operation
    {
        return $this->cache->get(
            self::getOperationCacheKey($controllerName),
            static fn () => null
        );
    }

    private static function getControllerName(ReflectionMethod $method): string
    {
        if ($method->name === '__invoke') {
            return sprintf('%s', $method->class);
        }

        return sprintf('%s::%s', $method->class, $method->name);
    }

    private static function getOperationCacheKey(string $controllerName): string
    {
        return self::CACHE_PREFIX.str_replace(['(', ')', '\\', ':'], '_', $controllerName);
    }

    private function isOperationResource(ReflectionMethod $reflection): bool
    {
        if ($reflection->getAttributes(Operation::class, ReflectionAttribute::IS_INSTANCEOF) !== []) {
            return true;
        }

        return false;
    }

    private function buildOperationFromMethod(ReflectionMethod $method, string $controller): Operation
    {
        $operation = $this->buildMethodOperation($method);

        if ($operation->getName() === null) {
            $operation->setName(
                $this->getDefaultRouteName($method->class, $method->name)
            );
        }

        $payloadResource = $this->isPayloadResource($method->getParameters());

        if ($payloadResource !== null) {
            if ($operation->getFilters() === []) {
                $operation->setFilters(
                    $this->buildParameterFilters($payloadResource)
                );

                if ($payloadResource->getType() === null) {
                    throw new ParamShouldBeTypedException($payloadResource->name);
                }

                /** @phpstan-ignore-next-line getName is an existing method */
                $inputTypeClassname = $payloadResource->getType()->getName();

                $operation->setIsPaginationEnabled(
                    $this->buildIsPaginationActivated($inputTypeClassname)
                );

                $operation->setInput($inputTypeClassname);
            }

            if ($operation->getDenormalizationContext() === []) {
                $operation->setDenormalizationContext(
                    $this->buildDenormalizationContext($payloadResource)
                );
            }
        }

        if ($method->getReturnType() !== null && $operation->getOutput() === null) {
            $operation->setOutput(
                /** @phpstan-ignore-next-line getName is an existing method */
                $this->buildOutput($method->getReturnType()->getName())
            );
        }

        if ($operation->getUriVariables() === []) {
            $operation->setUriVariables(
                $this->buildUriVariables($method->getParameters(), $operation->getPath())
            );
        }

        $operation->setController($controller);
        $operation->setControllerMethod($method->getName());

        return $operation;
    }

    /**
     * @param  class-string        $className
     * @throws ReflectionException
     */
    private function buildIsPaginationActivated(string $className): bool
    {
        return array_key_exists(
            PaginationQueryInputInterface::class,
            (new ReflectionClass($className))->getInterfaces()
        );
    }

    private function buildDenormalizationContext(ReflectionParameter $payload): array
    {
        foreach ($payload->getAttributes() as $attribute) {
            if ($attribute->getName() === MapRequestPayload::class || $attribute->getName() === MapQueryString::class) {
                /**
                 * @var MapRequestPayload|MapQueryString $mapRequestOrQuery
                 */
                $mapRequestOrQuery = $attribute->newInstance();

                return $mapRequestOrQuery->serializationContext;
            }
        }

        return [];
    }

    /**
     * @return array<ApiProperty>
     */
    private function buildParameterFilters(ReflectionParameter $parameter): array
    {
        $apiProperties = [];
        $inputClassName = $parameter->getType();

        if (!$inputClassName instanceof ReflectionNamedType) {
            return $apiProperties;
        }

        /** @phpstan-ignore-next-line parameter 1 is a class-string */
        $queryClass = new ReflectionClass($inputClassName->getName());

        foreach ($queryClass->getProperties() as $property) {
            $apiProperties[$property->getName()] = $this->buildApiProperty(
                $property
            );
        }

        return $apiProperties;
    }

    private function buildMethodOperation(ReflectionMethod $method): Operation
    {
        /**
         * @var ReflectionAttribute $methodAttribute
         */
        $methodAttribute = $method->getAttributes(Operation::class, ReflectionAttribute::IS_INSTANCEOF)[0];

        /** @var Operation */
        return $methodAttribute->newInstance();
    }

    /**
     * @param array<int, ReflectionParameter> $reflectionParameters
     */
    private function isPayloadResource(array $reflectionParameters): ?ReflectionParameter
    {
        foreach ($reflectionParameters as $reflectionParameter) {
            if ($reflectionParameter->getAttributes(MapQueryString::class, ReflectionAttribute::IS_INSTANCEOF) !== []) {
                return $reflectionParameter;
            }

            if ($reflectionParameter->getAttributes(MapRequestPayload::class, ReflectionAttribute::IS_INSTANCEOF) !== []) {
                return $reflectionParameter;
            }
        }

        return null;
    }

    private function getDefaultRouteName(string $class, string $method): string
    {
        $name = str_replace('\\', '_', $class).'_'.$method;

        return function_exists('mb_strtolower') && is_int(preg_match('//u', $name))
            ? mb_strtolower($name, 'UTF-8')
            : strtolower($name);
    }

    private function buildApiProperty(ReflectionProperty $property): ApiProperty
    {
        $apiProperty = new ApiProperty();

        foreach ($property->getAttributes() as $attribute) {
            if ($attribute->getName() === ApiProperty::class) {
                /**
                 * @var ApiProperty $apiProperty
                 */
                $apiProperty = $attribute->newInstance();
            }
        }

        return new ApiProperty(
            name: $apiProperty->getName() !== null ? $apiProperty->getName() : $property->getName(),
            type: $apiProperty->getType() !== null
                ? $apiProperty->getType()
                /** @phpstan-ignore-next-line getName will exist if getType is a ReflectionNamedType */
                : (!$property->getType() instanceof ReflectionNamedType ? $property->getType()->getName() : 'string'),
            required: $apiProperty->isRequired() !== null
                ? $apiProperty->isRequired()
                : ($property->getType() !== null && !$property->getType()->allowsNull()),
            description: $apiProperty->getDescription(),
            deprecated: $apiProperty->isDeprecated()
        );
    }

    /**
     * @param class-string $output
     */
    private function buildOutput(string $output): ?string
    {
        if (!class_exists($output)) {
            throw new ResourceClassNotFoundException($output);
        }

        if ($output === Response::class || $output === JsonResponse::class) {
            return null;
        }

        if ($output === DoctrinePagination::class) {
            return Pagination::class;
        }

        $outputClass = new ReflectionClass($output);

        if ($outputClass->isIterable()) {
            return Pagination::class;
        }

        return $output;
    }

    /**
     * @param array<ReflectionParameter> $parameters
     *
     * @return array<string, ApiProperty>
     */
    private function buildUriVariables(array $parameters, string $path): array
    {
        $parsedPathQuery = explode('/', $path);
        $uriVariables = [];

        foreach ($parameters as $parameter) {
            foreach ($parsedPathQuery as $query) {
                if ($this->isQueryResource($parameter, $query) === true) {
                    $uriVariables[$parameter->getName()] = new ApiProperty(
                        name: $parameter->getName(),
                        /** @phpstan-ignore-next-line getName is an existing method */
                        type: $parameter->getType() !== null ? $parameter->getType()->getName() : 'string',
                        required: !$parameter->isOptional(),
                        description: 'Uri parameter',
                    );
                }
            }
        }

        return $uriVariables;
    }

    private function isQueryResource(ReflectionParameter $parameter, string $query): bool
    {
        if ($this->isPayloadResource([$parameter]) !== null) {
            return false;
        }

        if (strcmp($parameter->getName(), str_replace(['{', '}'], '', $query)) === 0) {
            return true;
        }

        return false;
    }
}
