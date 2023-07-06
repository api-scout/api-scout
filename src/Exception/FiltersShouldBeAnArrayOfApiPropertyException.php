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

namespace ApiScout\Exception;

use Exception;
use function gettype;

/**
 * Resource class not found exception.
 *
 * @author Marvin Courcier <courciermarvin@gmail.com>
 */
final class FiltersShouldBeAnArrayOfApiPropertyException extends Exception implements DomainExceptionInterface
{
    public function __construct(mixed $filter)
    {
        parent::__construct(
            sprintf('filters should be an array of ApiProperty, "%s" given.', gettype($filter))
        );
    }
}
