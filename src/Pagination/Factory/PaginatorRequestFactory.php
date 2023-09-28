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

namespace ApiScout\Pagination\Factory;

use ApiScout\OpenApi\Model\PaginationOptions;
use ApiScout\Operation;
use Symfony\Component\HttpFoundation\Request;

final class PaginatorRequestFactory implements PaginatorRequestFactoryInterface
{
    public function __construct(
        private readonly PaginationOptions $paginationOptions,
    ) {
    }

    public function getCurrentPage(Request $request): int
    {
        $currentPage = $request->get($this->paginationOptions->getPaginationPageParameterName());

        if (!is_numeric($currentPage)) {
            return 1;
        }

        return (int) $currentPage;
    }

    public function getItemsPerPage(Operation $operation): int
    {
        return $operation->getPaginationItemsPerPage() ?? $this->paginationOptions->getPaginationItemsPerPage();
    }
}
