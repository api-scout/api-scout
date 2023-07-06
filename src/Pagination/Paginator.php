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

namespace ApiScout\Pagination;

use Countable;
use function array_slice;
use function count;

/**
 * @template ITEM of object
 *
 * @implements \IteratorAggregate<ITEM>
 */
class Paginator implements Countable, PaginatorInterface
{
    protected string $type;

    protected readonly int $currentPage;

    public function __construct(
        protected readonly iterable $items,
        int $currentPage,
        protected readonly int $itemsPerPage,
    ) {
        $this->currentPage = $this->setCurrentPage($currentPage);
    }

    public function count(): int
    {
        return count((array) $this->items);
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getContent(): array
    {
        return array_slice(
            (array) $this->items,
            $this->getOffset(),
            $this->itemsPerPage
        );
    }

    public function toArray(): array
    {
        return [
            'data' => $this->getContent(),
            'pagination' => [
                'currentPage' => $this->currentPage,
                'nextPage' => $this->currentPage + 1,
                'itemsPerPage' => $this->itemsPerPage,
                'totalItems' => $this->count(),
            ],
        ];
        // firstPage, lastPage, CurrentPage, nextPage

        // Doit peut-être être des lien
    }

    private function setCurrentPage(int $currentPage): int
    {
        return $this->isValidPageNumber($currentPage) ? $currentPage : 1;
    }

    private function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    /**
     * Determine if the given value is a valid page number.
     */
    private function isValidPageNumber(int $page): bool
    {
        return $page >= 1 && filter_var($page, \FILTER_VALIDATE_INT) !== false;
    }
}
