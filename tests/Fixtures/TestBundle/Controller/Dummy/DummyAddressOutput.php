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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy;

final class DummyAddressOutput
{
    public function __construct(
        public readonly string $street,
        public readonly string $zipCode,
        public readonly string $city,
        public readonly string $country,
    ) {
    }
}
