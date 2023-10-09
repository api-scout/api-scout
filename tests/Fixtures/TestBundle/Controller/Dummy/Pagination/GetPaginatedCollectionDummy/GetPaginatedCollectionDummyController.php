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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Pagination\GetPaginatedCollectionDummy;

use ApiScout\Attribute\GetCollection;
use ApiScout\Response\Pagination\Pagination;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyQueryInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

use function array_slice;
use function count;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetPaginatedCollectionDummyController extends AbstractController
{
    #[GetCollection('/paginated_dummies', name: 'app_get_dummy_paginated_collection', resource: Dummy::class)]
    public function __invoke(
        #[MapQueryString] DummyQueryInput $query,
    ): Pagination {
        $pinkFloydCollection = [];

        for ($i = 0; $i < 31; ++$i) {
            $pinkFloydCollection[] =
                new DummyOutput(
                    $i,
                    'Pink',
                    'Floyd',
                    '25-05-1993',
                    '01H3VY5WDTNNK2MDBSD23EK0HS',
                    'de0215dd-23a6-42ca-8732-b341da0d07d9',
                    new DummyAddressOutput(
                        '127 avenue of the street',
                        '13100',
                        'California',
                        'US'
                    )
                );
        }

        $slicedPinkFloydCollection = array_slice(
            $pinkFloydCollection,
            ($query->getPage() - 1) * $query->getItemsPerPage(),
            $query->getItemsPerPage()
        );

        return new Pagination(
            $slicedPinkFloydCollection,
            $query->getPage(),
            $query->getItemsPerPage(),
            count($pinkFloydCollection)
        );
    }
}
