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

// use Ramsey\Uuid\Uuid;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyAddressOutput;
use DateTime;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class DummyAttributePayloadInput
{
    public function __construct(
        #[Assert\NotBlank()]
        public readonly string $firstName,
        public readonly ?string $lastName,
        public readonly DateTime $age,
        public readonly ?Ulid $ulid,
        public readonly ?Uuid $uuid,
        #[Assert\NotBlank()]
        public readonly DummyAddressOutput $address,
    ) {
    }
}
