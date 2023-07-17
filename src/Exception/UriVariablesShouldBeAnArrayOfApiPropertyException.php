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
 * UriVariables should be an array of api property exception.
 *
 * @author Marvin Courcier <courciermarvin@gmail.com>
 */
final class UriVariablesShouldBeAnArrayOfApiPropertyException extends Exception implements DomainExceptionInterface
{
    public function __construct(mixed $uriVariable)
    {
        parent::__construct(
            sprintf('uriVariables should be an array of ApiProperty, "%s" given.', gettype($uriVariable))
        );
    }
}
