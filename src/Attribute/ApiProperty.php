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

namespace ApiScout\Attribute;

use Attribute;

/**
 * ApiProperty
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::TARGET_CLASS_CONSTANT)]
final class ApiProperty
{
    public function __construct(
        private readonly ?string $name = null,
        private readonly ?string $type = null,
        private readonly ?bool $required = null,
        private readonly string $description = '',
        private readonly bool $deprecated = false,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }
}
