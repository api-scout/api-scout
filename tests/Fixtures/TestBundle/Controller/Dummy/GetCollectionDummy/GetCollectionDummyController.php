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

use ApiScout\Attribute\GetCollection;
use ApiScout\Response\Pagination\QueryInput\PaginationQueryInput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyQueryInput;
use ArrayObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

use function array_slice;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetCollectionDummyController extends AbstractController
{
    #[GetCollection('/dummies', name: 'app_get_dummy_collection', resource: Dummy::class)]
    public function __invoke(
        #[MapQueryString] ?DummyQueryInput $query,
        #[MapQueryString] PaginationQueryInput $paginationInput,
    ): ArrayObject {
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

        return new ArrayObject(array_slice(
            $pinkFloydCollection,
            ($paginationInput->getPage() - 1) * $paginationInput->getItemsPerPage(),
            $paginationInput->getItemsPerPage()
        ));
    }
}
