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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\DummyAttribute;

use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;

final class DummyAttributeOutput
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $age,
        public readonly ?string $ulid,
        public readonly ?string $uuid,
        public readonly DummyAddressOutput $address,
    ) {
    }
}
