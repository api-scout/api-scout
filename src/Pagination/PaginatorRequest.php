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

namespace ApiScout\Pagination;

use ApiScout\OpenApi\Model\PaginationOptions;
use ApiScout\Operation;
use LogicException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The Paginator Request Factory.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PaginatorRequest implements PaginatorRequestInterface
{
    public function __construct(
        private readonly PaginationOptions $paginationOptions,
        private readonly RequestStack $requestStack
    ) {
    }

    public function getCurrentPage(): int
    {
        $request = $this->requestStack->getMainRequest();

        if ($request === null) {
            throw new LogicException('No request');
        }
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
