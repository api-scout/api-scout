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

namespace ApiScout\OpenApi;

use function array_key_exists;

final class SchemaRefNameGenerator
{
    public static function generate(string $className, string $entityName, array $groups = []): string
    {
        if ($groups !== [] && array_key_exists('groups', $groups)) {
            return self::buildDefinitionName(self::buildPrefixName($groups['groups']), $entityName);
        }

        return self::buildDefinitionName($entityName, $className);
    }

    private static function buildDefinitionName(string $className, string $entityName): string
    {
        return self::normalizeClassName($entityName).'.'.self::normalizeClassName($className);
    }

    private static function buildPrefixName(array $groups): string
    {
        $name = preg_replace('/[^A-Za-z0-9\-]/', '-', $groups[0]);
        $names = explode('-', $name);

        $prefixName = '';

        foreach ($names as $explodedName) {
            if ($explodedName !== '') {
                $prefixName .= ucwords($explodedName);
            }
        }

        return $prefixName;
    }

    private static function normalizeClassName(string $classname): string
    {
        $classNameTab = explode('\\', $classname);

        return end($classNameTab);
    }
}
