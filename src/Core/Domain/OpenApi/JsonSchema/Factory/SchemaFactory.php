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

use ApiScout\Core\Domain\OpenApi\JsonSchema\JsonSchema;
use ApiScout\Core\Domain\OpenApi\JsonSchema\PropertyTypeBuilderTrait;
use ApiScout\Core\Domain\Utils\ClassNameNormalizerTrait;
use ReflectionClass;
use Symfony\Component\PropertyInfo\Type;

final class SchemaFactory implements SchemaFactoryInterface
{
    use ClassNameNormalizerTrait;
    use PropertyTypeBuilderTrait;
    private const BASE_TEMPLATE = [
        'type' => 'object',
        'description' => '',
        'deprecated' => false,
        'required' => [],
        'properties' => [],
    ];

    /**
     * @param class-string $className
     */
    public function buildSchema(string $className, string $entityName): JsonSchema
    {
        $schema = new JsonSchema();

        $schemaProperties = [
            ...self::BASE_TEMPLATE,
            ...$this->buildOpenApiPropertiesFromClass($className),
        ];

        $schema->offsetSet(
            $this->buildDefinitionName($className, $entityName),
            $schemaProperties
        );

        return $schema;
    }

    /**
     * @param class-string $className
     */
    private function buildOpenApiPropertiesFromClass(string $className): array
    {
        $reflectionClass = new ReflectionClass($className);

        $schemaProperties = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $schemaProperty = [];

            if ($property->getType() === null) {
                continue;
            }

            if ($property->isReadOnly()) {
                $schemaProperty['readonly'] = true;
            }

            if (!$property->getType()->allowsNull()) {
                $schemaProperties['required'][] = $property->getName();
            }

            $schemaProperties['properties'][$property->getName()] = [
                ...$schemaProperty,
                ...$this->buildPropertyType(
                    $property->getType()->getName(), /** @phpstan-ignore-line getName method exists */
                    $property->getType()->allowsNull()
                ),
            ];
        }

        return $schemaProperties;
    }

    private function buildPropertyType(?string $type, bool $isNullable): array
    {
        return match ($type) {
            Type::BUILTIN_TYPE_STRING => ['type' => 'string'],
            Type::BUILTIN_TYPE_INT => ['type' => 'integer'],
            Type::BUILTIN_TYPE_FLOAT => ['type' => 'number'],
            Type::BUILTIN_TYPE_BOOL, Type::BUILTIN_TYPE_FALSE, Type::BUILTIN_TYPE_TRUE => ['type' => 'boolean'],
            Type::BUILTIN_TYPE_ARRAY => ['items' => ['type' => 'string']],
            default => $this->getClassType($type, $isNullable),
        };
    }

    private function getClassType(?string $type, bool $isNullable): array
    {
        $openApiType = $this->getBasicClassType($type, $isNullable);

        if ($openApiType !== []) {
            return $openApiType;
        }

        if ($type !== null && class_exists($type)) {
            return $this->buildOpenApiPropertiesFromClass($type);
        }

        return ['type' => 'string'];
    }

    private function buildDefinitionName(string $className, string $entityName): string
    {
        return $this->normalizeClassName($entityName).'.'.$this->normalizeClassName($className);
    }
}
