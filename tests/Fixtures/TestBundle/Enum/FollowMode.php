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

namespace ApiScout\Tests\Fixtures\TestBundle\Enum;

enum FollowMode: string
{
    case NEEDS_NO_VALIDATION = 'NEEDS_NO_VALIDATION';
    case NEEDS_VALIDATION = 'NEEDS_VALIDATION';
    case PRIVATE = 'PRIVATE';
}
