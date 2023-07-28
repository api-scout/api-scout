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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\GetCollectionDummy;

use ApiScout\Pagination\Paginator;
use ApiScout\Pagination\PaginatorInterface;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;

/**
 * @extends Paginator<DummyOutput>
 */
final class DummyCollectionOutput extends Paginator implements PaginatorInterface
{
    public function __construct(iterable $items, int $currentPage, int $itemsPerPage)
    {
        $this->type = DummyOutput::class;

        parent::__construct($items, $currentPage, $itemsPerPage);
    }
}
