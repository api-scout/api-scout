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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\GetDummy;

use ApiScout\Attribute\Get;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GetDummyController extends AbstractController
{
    #[Get(
        path: '/dummies/{id}',
        name: 'app_get_dummy',
        class: Dummy::class
    )]
    public function __invoke(
        int $id,
    ): DummyOutput {
        return new DummyOutput(
            1,
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
}
