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

namespace ApiScout\OpenApi\Model;

final class PaginationOptions
{
    public function __construct(
        private readonly bool $paginationEnabled = true,
        private readonly string $paginationPageParameterName = 'page',
        private readonly int $paginationItemsPerPage = 10,
        private readonly ?int $paginationMaximumItemsPerPage = null,
    ) {
    }

    public function isPaginationEnabled(): bool
    {
        return $this->paginationEnabled;
    }

    public function getPaginationPageParameterName(): string
    {
        return $this->paginationPageParameterName;
    }

    public function getPaginationItemsPerPage(): int
    {
        return $this->paginationItemsPerPage;
    }

    public function getPaginationMaximumItemsPerPage(): ?int
    {
        return $this->paginationMaximumItemsPerPage;
    }
}
