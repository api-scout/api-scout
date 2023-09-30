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

use ApiScout\OpenApi\Model\PaginationOptions;

/**
 * Configure the PaginationOptions through service
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PaginationOptionsConfigurator
{
    public function __construct(
        private readonly bool $paginationEnabled = true,
        private readonly string $paginationPageParameterName = 'page',
        private readonly int $paginationItemsPerPage = 10,
        private readonly ?int $paginationMaximumItemsPerPage = null,
    ) {
    }

    public function getPaginationOptions(): PaginationOptions
    {
        return new PaginationOptions(
            $this->paginationEnabled,
            $this->paginationPageParameterName,
            $this->paginationItemsPerPage,
            $this->paginationMaximumItemsPerPage
        );
    }
}
