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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\DummyEntity;

use Symfony\Component\Serializer\Annotation\Groups;

final class DummyCompanyEntity
{
    public function __construct(
        #[Groups(['dummy::read'])]
        public ?int $id,
        #[Groups(['dummy::read', 'dummy::write'])]
        public string $name,
        #[Groups(['dummy::read', 'dummy::write'])]
        public string $description,
    ) {
    }
}
