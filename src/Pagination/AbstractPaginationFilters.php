<?php

namespace ApiScout\Pagination;

abstract class AbstractPaginationFilters
{
    public function __construct(
        public readonly int $page = 1,
    ) {
    }
}
