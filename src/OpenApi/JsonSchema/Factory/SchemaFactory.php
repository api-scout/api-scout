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

use ApiScout\OpenApi\JsonSchema\JsonSchema;
use ApiScout\OpenApi\JsonSchema\Trait\PropertyTypeBuilderTrait;
use ApiScout\OpenApi\Trait\ClassNameNormalizerTrait;
use ReflectionClass;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;

/**
 * Build the Input and Output OpenApi Specification schema.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
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

    private array $groups;

    public function __construct(
        private readonly ClassMetadataFactoryInterface $metadata
    ) {
        $this->groups = [];
    }

    /**
     * @param class-string                 $className
     * @param array<string, array<string>> $groups
     */
    public function buildSchema(
        string $className,
        string $entityName,
        array $groups,
        string $prefix = 'Input'
    ): JsonSchema {
        $schema = new JsonSchema();

        $this->groups = $groups['groups'] ?? [];

        $schemaKey = $this->buildDefinitionName($className, $entityName);

        dd();

        $schema->offsetSet(
            $schemaKey,
            [
                ...self::BASE_TEMPLATE,
                ...$this->buildOpenApiPropertiesFromClass($className),
            ]
        );

        return $schema;
    }

    /**
     * @param class-string $className
     */
    private function buildOpenApiPropertiesFromClass(string $className): array
    {
        $properties = (new ReflectionClass($className))->getProperties();
        $classMetadata = $this->metadata->getMetadataFor($className);
        $attributesMetadata = $classMetadata->getAttributesMetadata();

        $schemaProperties = [];

        foreach ($properties as $property) {
            if ($this->groups !== []
                && array_diff($this->groups, $attributesMetadata[$property->getName()]->getGroups()) !== []) {
                continue;
            }

            if ($property->getType() === null) {
                continue;
            }

            $schemaProperty = [];

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
