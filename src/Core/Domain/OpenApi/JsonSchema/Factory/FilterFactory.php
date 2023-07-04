<?php

declare(strict_types=1);

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiScout\Core\Domain\OpenApi\JsonSchema\Factory;

use ApiScout\Core\Domain\OpenApi\JsonSchema\PropertyTypeBuilderTrait;
use ApiScout\Core\Domain\OpenApi\Model;
use ApiScout\Core\Domain\Operation;
use ReflectionClass;
use RuntimeException;

final class FilterFactory implements FilterFactoryInterface
{
    use PropertyTypeBuilderTrait;

    public function __construct(
        private readonly Model\PaginationOptions $paginationOptions,
    ) {
    }

    public function buildPathFilter(array $uriVariables, Model\Operation $openapiOperation): Model\Operation
    {
        foreach ($uriVariables as $uriVariableName => $uriVariableType) {
            $parameter = new Model\Parameter(
                $uriVariableName,
                'path',
                '',
                true,
                false,
                false,
                $this->getClassType($uriVariableType)
            );

            if ($this->hasParameter($openapiOperation, $parameter)) {
                continue;
            }

            $openapiOperation = $openapiOperation->withParameter($parameter);
        }

        return $openapiOperation;
    }

    /**
     * @param class-string $className
     */
    public function buildQueryFilters(string $className, Model\Operation $openapiOperation): Model\Operation
    {
        $parametersClass = new ReflectionClass($className);

        foreach ($parametersClass->getProperties() as $property) {
            $parameter = new Model\Parameter(
                $property->getName(),
                'query',
                '',
                $property->getType() !== null ? $property->getType()->allowsNull() !== true : false,
                false,
                false,
                $property->getType() !== null
                    /** @phpstan-ignore-next-line getName method does exists */
                    ? $this->getClassType($property->getType()->getName())
                    : ['type' => 'string']
            );

            if ($this->hasParameter($openapiOperation, $parameter)) {
                continue;
            }

            $openapiOperation = $openapiOperation->withParameter($parameter);
        }

        return $openapiOperation;
    }

    public function buildPaginationParameters(
        Operation $operation,
        Model\Operation $openApiOperation,
    ): Model\Operation {
        if (!$this->paginationOptions->isPaginationEnabled()) {
            return $openApiOperation;
        }

        $parameters = [];

        if ($operation->isPaginationEnabled() ?? $this->paginationOptions->isPaginationEnabled()) {
            $parameters[] = new Model\Parameter(
                $this->paginationOptions->getPaginationPageParameterName(),
                'query',
                'The collection page number',
                false,
                false,
                true,
                ['type' => 'integer', 'default' => 1]
            );

            if ($operation->getPaginationItemsPerPage() ?? $this->paginationOptions->getPaginationItemsPerPage()) {
                $schema = [
                    'type' => 'integer',
                    'default' => $operation->getPaginationItemsPerPage() ?? $this->paginationOptions->getPaginationItemsPerPage(),
                    'minimum' => 0,
                ];

                if (null !== $maxItemsPerPage = ($operation->getPaginationMaximumItemsPerPage() ?? $this->paginationOptions->getPaginationMaximumItemsPerPage())) {
                    $schema['maximum'] = $maxItemsPerPage;
                }

                $parameters[] = new Model\Parameter(
                    $this->paginationOptions->getPaginationPageParameterName(),
                    'query',
                    'The number of items per page',
                    false,
                    false,
                    true,
                    $schema
                );
            }
        }

        //        if ($operation->getPaginationClientEnabled() ?? $this->paginationOptions->isPaginationClientEnabled()) {
        //            $parameters[] = new Model\Parameter(
        //                $this->paginationOptions->getPaginationClientEnabledParameterName(),
        //                'query',
        //                'Enable or disable pagination',
        //                false,
        //                false,
        //                true,
        //                ['type' => 'boolean']
        //            );
        //        }
        //      dd(
        //          $operation,
        //          $parameters
        //      );

        foreach ($parameters as $parameter) {
            if ($this->hasParameter($openApiOperation, $parameter)) {
                continue;
            }

            $openApiOperation = $openApiOperation->withParameter($parameter);
        }

        return $openApiOperation;
    }

    private function getClassType(?string $type): array
    {
        $openApiType = $this->buildPropertyType($type);

        if ($openApiType !== []) {
            return $openApiType;
        }

        if ($type !== null && class_exists($type)) {
            return [
                'type' => 'string',
                'format' => $this->buildTypeFormatName($type),
            ];
        }

        return ['type' => 'string'];
    }

    private function hasParameter(Model\Operation $operation, Model\Parameter $parameter): bool
    {
        foreach ($operation->getParameters() as $existingParameter) {
            if ($existingParameter->getName() === $parameter->getName() && $existingParameter->getIn() === $parameter->getIn()) {
                return true;
            }
        }

        return false;
    }

    private function buildTypeFormatName(string $type): string
    {
        $typeExploded = explode('\\', $type);
        $upperClassName = end($typeExploded);

        if ($upperClassName === false) {
            throw new RuntimeException('Could not buildTypeFormatName with '.$type);
        }

        return strtolower($upperClassName);
    }
}
