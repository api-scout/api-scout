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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Pagination\GetDummyArray;

use ApiScout\Attribute\Get;
use ApiScout\OpenApi\Model;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDummyArrayController extends AbstractController
{
    #[Get(
        '/dummies_array',
        name: 'app_get_dummy_array',
        resource: Dummy::class,
        openapi: new Model\Operation(
            summary: 'Retrieve a Dummy array',
            description: 'Retrieve a Dummy array'
        ),
        paginationEnabled: false
    )]
    public function __invoke(): array
    {
        $pinkFloydArray = [];

        for ($i = 0; $i < 5; ++$i) {
            $pinkFloydArray[] =
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

        return $pinkFloydArray;
    }
}
