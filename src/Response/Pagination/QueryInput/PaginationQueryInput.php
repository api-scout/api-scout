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

namespace ApiScout\Response\Pagination\QueryInput;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
class PaginationQueryInput implements PaginationQueryInputInterface
{
    public function __construct(
        protected readonly int $page = 1,
        protected readonly int $itemsPerPage = 10,
    ) {
    }

    public function __toArray(): array
    {
        return [
            $this->page,
            $this->itemsPerPage,
        ];
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
