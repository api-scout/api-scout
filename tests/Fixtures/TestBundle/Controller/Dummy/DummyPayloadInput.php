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

// use Ramsey\Uuid\Uuid;
use ApiScout\Attribute\ApiProperty;
use DateTime;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class DummyPayloadInput
{
    public function __construct(
        #[Assert\NotBlank()]
        public readonly string $firstName,
        #[ApiProperty(description: 'input lastname', deprecated: true)]
        public readonly ?string $lastName,
        #[ApiProperty(description: 'input lastname')]
        public readonly DateTime $age,
        public readonly ?Ulid $ulid,
        public readonly ?Uuid $uuid,
        #[Assert\NotBlank()]
        public readonly DummyAddressOutput $address,
    ) {
    }
}
