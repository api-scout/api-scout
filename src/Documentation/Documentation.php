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

namespace ApiScout\Documentation;

use ApiScout\Operations;

/**
 * Generates the API documentation.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class Documentation implements DocumentationInterface
{
    public function __construct(
        private readonly Operations $operations,
        private readonly string $title,
        private readonly string $description,
        private readonly string $version
    ) {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOperation(): Operations
    {
        return $this->operations;
    }
}
