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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Pagination\GetDummyIterable;

use ApiScout\Attribute\Get;
use ApiScout\OpenApi\Model;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDummyIterableController extends AbstractController
{
    #[Get(
        '/dummies_iterable',
        name: 'app_get_dummy_iterable',
        resource: Dummy::class,
        openapi: new Model\Operation(
            summary: 'Retrieve a Dummy Iterable',
            description: 'Retrieve a Dummy Iterable'
        ),
        paginationEnabled: false
    )]
    public function __invoke(): iterable
    {
        $pinkFloydIterable = [];

        for ($i = 0; $i < 5; ++$i) {
            $pinkFloydIterable[] =
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

        return $pinkFloydIterable;
    }
}
