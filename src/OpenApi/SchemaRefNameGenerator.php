<?php

namespace ApiScout\OpenApi;

final class SchemaRefNameGenerator
{
    public static function generate(string $className, string $entityName, array $groups = []): string
    {
        if ($groups !== []) {
            return self::buildDefinitionName(self::buildPrefixName($groups), $entityName);
        }

        return self::buildDefinitionName($className, $entityName);
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
