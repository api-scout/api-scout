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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\PutDummy;

use ApiScout\Core\Domain\Attribute\Put;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyPayloadInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final class PutDummyController extends AbstractController
{
    #[Put(path: '/dummies', name: 'app_update_put_dummy', class: Dummy::class)]
    public function __invoke(
        #[MapRequestPayload] DummyPayloadInput $dummyPayloadInput,
    ): DummyOutput {
        return new DummyOutput(
            1,
            $dummyPayloadInput->firstName,
            $dummyPayloadInput->lastName ?? '',
            '25-05-1993',
            '01H3VY5WDTNNK2MDBSD23EK0HS',
            'de0215dd-23a6-42ca-8732-b341da0d07d9',
            new DummyAddressOutput(
                $dummyPayloadInput->address->street,
                $dummyPayloadInput->address->zipCode,
                $dummyPayloadInput->address->city,
                $dummyPayloadInput->address->country,
            )
        );
    }
}
