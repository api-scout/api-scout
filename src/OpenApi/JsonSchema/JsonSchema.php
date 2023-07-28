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

namespace ApiScout\OpenApi\JsonSchema;

use ArrayObject;

use function is_string;

/**
 * Represents a JSON Schema document.
 *
 * Both the standard version and the OpenAPI flavors (v2 and v3) are supported.
 *
 * @see https://json-schema.org/latest/json-schema-core.html
 * @see https://github.com/OAI/OpenAPI-Specification
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class JsonSchema extends ArrayObject
{
    public const TYPE_INPUT = 'input';
    public const TYPE_OUTPUT = 'output';
    public const VERSION_JSON_SCHEMA = 'json-schema';
    public const VERSION_OPENAPI = 'openapi';
    public const VERSION_SWAGGER = 'swagger';

    public function __construct(private readonly string $version = self::VERSION_JSON_SCHEMA)
    {
        parent::__construct();
    }

    /**
     * The flavor used for this document: JSON Schema, OpenAPI v2 or OpenAPI v3.
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     *
     * @param bool $includeDefinitions if set to false, definitions will not be included in the resulting array
     */
    public function getArrayCopy(bool $includeDefinitions = true): array
    {
        $schema = parent::getArrayCopy();

        if (!$includeDefinitions) {
            unset($schema['definitions'], $schema['components']);
        }

        return $schema;
    }

    /**
     * Retrieves the definitions used by this schema.
     */
    public function getDefinitions(): ArrayObject
    {
        /**
         * @var ArrayObject $definitions
         *
         * @phpstan-ignore-next-line this component will always be an array
         */
        $definitions = $this['definitions'] ?? $this['components']['schemas'] ?? new ArrayObject();

        $this->setDefinitions($definitions);

        return $definitions;
    }

    /**
     * Associates existing definitions to this schema.
     */
    public function setDefinitions(ArrayObject $definitions): void
    {
        if ($this->version === self::VERSION_OPENAPI) {
            /** @phpstan-ignore-next-line this component will always be an array */
            $this['components']['schemas'] = $definitions;

            return;
        }

        $this['definitions'] = $definitions;
    }

    /**
     * Returns the name of the root definition, if defined.
     */
    public function getRootDefinitionKey(): ?string
    {
        $ref = $this['$ref'];

        if (!isset($ref)) {
            return null;
        }

        if (!is_string($ref)) {
            return null;
        }

        return $this->removeDefinitionKeyPrefix($ref);
    }

    /**
     * Returns the name of the items definition, if defined.
     */
    public function getItemsDefinitionKey(): ?string
    {
        /** @phpstan-ignore-next-line this items will always be an array */
        $ref = $this['items']['$ref'] ?? null;
        if ($ref === null) {
            return null;
        }

        if (!is_string($ref)) {
            return null;
        }

        return $this->removeDefinitionKeyPrefix($ref);
    }

    /**
     * Checks if this schema is initialized.
     */
    public function isDefined(): bool
    {
        return isset($this['$ref']) || isset($this['type']);
    }

    private function removeDefinitionKeyPrefix(string $definitionKey): string
    {
        // strlen('#/definitions/') = 14
        // strlen('#/components/schemas/') = 21
        $prefix = $this->version === self::VERSION_OPENAPI ? 21 : 14;

        return substr($definitionKey, $prefix);
    }
}
