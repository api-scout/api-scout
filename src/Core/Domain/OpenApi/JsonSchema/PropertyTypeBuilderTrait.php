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

namespace ApiScout\Core\Domain\OpenApi\JsonSchema;

use BackedEnum;
use DateInterval;
use DateTimeInterface;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use SplFileInfo;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

use function is_string;

trait PropertyTypeBuilderTrait
{
    private function buildPropertyType(?string $type, bool $isNullable = false): array
    {
        return match ($type) {
            Type::BUILTIN_TYPE_STRING => ['type' => 'string'],
            Type::BUILTIN_TYPE_INT => ['type' => 'integer'],
            Type::BUILTIN_TYPE_FLOAT => ['type' => 'number'],
            Type::BUILTIN_TYPE_BOOL, Type::BUILTIN_TYPE_FALSE, Type::BUILTIN_TYPE_TRUE => ['type' => 'boolean'],
            Type::BUILTIN_TYPE_ARRAY => ['items' => ['type' => 'string']],
            default => $this->getBasicClassType($type, $isNullable),
        };
    }

    private function getBasicClassType(?string $type, bool $isNullable = false): array
    {
        if ($type === null) {
            return [];
        }

        if (is_subclass_of($type, JsonSerializable::class)) {
            return ['type' => 'string'];
        }

        if (is_a($type, DateTimeInterface::class, true)) {
            return [
                'type' => 'string',
                'format' => 'date-time',
            ];
        }
        if (is_a($type, DateInterval::class, true)) {
            return [
                'type' => 'string',
                'format' => 'duration',
            ];
        }
        if (is_a($type, UuidInterface::class, true) || is_a($type, Uuid::class, true)) {
            return [
                'type' => 'string',
                'format' => 'uuid',
            ];
        }
        if (is_a($type, Ulid::class, true)) {
            return [
                'type' => 'string',
                'format' => 'ulid',
            ];
        }
        if (is_a($type, SplFileInfo::class, true)) {
            return [
                'type' => 'string',
                'format' => 'binary',
            ];
        }

        if (is_a($type, BackedEnum::class, true)) {
            $enumCases = array_map(static fn (BackedEnum $enum): string|int => $enum->value, $type::cases());

            $type = is_string($enumCases[0] ?? '') ? 'string' : 'int';

            if ($isNullable) {
                $enumCases[] = null;
            }

            return [
                'type' => $type,
                'enum' => $enumCases,
            ];
        }

        return [];
    }
}
