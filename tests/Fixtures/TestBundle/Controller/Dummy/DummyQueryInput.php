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

use ApiScout\Attribute\ApiProperty;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class DummyQueryInput
{
    public function __construct(
        #[ApiProperty(description: 'The name of the champion')]
        public readonly ?string $name = '',
        /** @var string|null $city The name of the city */
        public readonly ?string $city = '',
    ) {
    }
}
