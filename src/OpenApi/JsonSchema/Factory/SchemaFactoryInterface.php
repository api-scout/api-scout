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

/**
 * Interface to build the Input and Output OpenApi Specification schema.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
interface SchemaFactoryInterface
{
    /**
     * @param class-string                 $className
     * @param array<string, array<string>> $groups
     */
    public function buildSchema(string $className, string $entityName, array $groups, string $prefix = 'Input'): JsonSchema;
}
