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

use LogicException;

use function count;

class Pagination implements PaginationInterface
{
    protected string $type;

    public function __construct(
        public readonly iterable $items,
        public readonly int $currentPage,
        public readonly int $itemsPerPage,
        public readonly int $totalItem
    ) {
        if (count((array) $this->items) > $itemsPerPage) {
            throw new LogicException(sprintf('Total items %d should not be superior to %d', count((array) $this->items), $itemsPerPage));
        }
    }

    public function getItems(): iterable
    {
        return $this->items;
    }

    public function getMetadata(): array
    {
        return [
            'currentPage' => $this->currentPage,
            'itemsPerPage' => $this->itemsPerPage,
            'totalItem' => $this->totalItem,
        ];
    }
}
