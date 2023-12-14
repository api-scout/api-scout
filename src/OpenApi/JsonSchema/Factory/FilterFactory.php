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

namespace ApiScout\OpenApi\JsonSchema\Factory;

use ApiScout\OpenApi\JsonSchema\Trait\PropertyTypeBuilderTrait;
use ApiScout\OpenApi\Model;
use RuntimeException;

/**
 * Build the Parameter model path and query for OpenApi specification.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class FilterFactory implements FilterFactoryInterface
{
    use PropertyTypeBuilderTrait;

    public const PATH = 'path';
    public const QUERY = 'query';

    public function buildUriParams(
        string $type,
        array $uriParams,
        Model\Operation $openapiOperation,
    ): Model\Operation {
        foreach ($uriParams as $uriParam) {
            $parameter = new Model\Parameter(
                $uriParam->getName() ?? '',
                $type,
                $uriParam->getDescription(),
                $uriParam->isRequired() ?? true,
                $uriParam->isDeprecated(),
                false,
                $this->getClassType($uriParam->getType() ?? 'string'),
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

        if ([] !== $openApiType) {
            return $openApiType;
        }

        if (null !== $type && class_exists($type)) {
            return [
                'format' => $this->buildTypeFormatName($type),
                'type' => 'string',
            ];
        }

        return ['type' => 'string'];
    }

    private function hasParameter(Model\Operation $operation, Model\Parameter $parameter): bool
    {
        /** @phpstan-ignore-next-line up to this point getParameters should be iterable */
        foreach ($operation->getParameters() as $existingParameter) {
            if ($existingParameter->getName() === $parameter->getName()
                && $existingParameter->getIn() === $parameter->getIn()) {
                return true;
            }
        }

        return false;
    }

    private function buildTypeFormatName(string $type): string
    {
        $typeExploded = explode('\\', $type);
        $upperClassName = end($typeExploded);

        if (false === (bool) $upperClassName) {
            throw new RuntimeException('Could not buildTypeFormatName with '.$type);
        }

        return strtolower($upperClassName);
    }
}
