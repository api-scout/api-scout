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

namespace ApiScout\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::TARGET_CLASS_CONSTANT)]
final class ApiProperty
{
    public function __construct(
        private readonly string $name,
        private readonly string $type = 'string',
        private readonly bool $required = true,
        private readonly ?string $description = null,
        private readonly bool $deprecated = false,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }
}
