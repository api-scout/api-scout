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
use RuntimeException;

final class FilterFactory implements FilterFactoryInterface
{
    use PropertyTypeBuilderTrait;

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

    public function buildQueryFilters(array $operationFilters, Model\Operation $openapiOperation): Model\Operation
    {
        foreach ($operationFilters as $apiProperty) {
            $parameter = new Model\Parameter(
                $apiProperty->getName(),
                'query',
                $apiProperty->getDescription() ?? '',
                $apiProperty->isRequired(),
                $apiProperty->isDeprecated(),
                false,
                $this->getClassType($apiProperty->getType())
            );

            if ($this->hasParameter($openapiOperation, $parameter)) {
                continue;
            }

            $openapiOperation = $openapiOperation->withParameter($parameter);
        }

        return $openapiOperation;
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

        if ((bool) $upperClassName === false) {
            throw new RuntimeException('Could not buildTypeFormatName with '.$type);
        }

        return strtolower($upperClassName);
    }
}
