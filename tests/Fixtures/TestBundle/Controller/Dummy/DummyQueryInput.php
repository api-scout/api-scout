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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy;

use ApiScout\Attribute\ApiProperty;

final class DummyQueryInput
{
    public function __construct(
        #[ApiProperty(name: 'name', type: 'string', required: false, description: 'The name of the champion')]
        public readonly ?string $name = '',
        public readonly ?string $city = '',
        #[ApiProperty(name: 'page', type: 'integer', required: true, description: 'The page my mate')]
        public readonly int $page = 1,
    ) {
    }
}
