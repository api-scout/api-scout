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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\DummyAttribute;

use ApiScout\Pagination\Paginator;
use ApiScout\Pagination\PaginatorInterface;

/**
 * @extends Paginator<DummyAttributeOutput>
 */
final class DummyAttributeCollectionOutput extends Paginator implements PaginatorInterface
{
    public function __construct(iterable $items, int $currentPage, int $itemsPerPage)
    {
        $this->type = DummyAttributeOutput::class;

        parent::__construct($items, $currentPage, $itemsPerPage);
    }
}
