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

namespace ApiScout\Exception;

use Exception;

/**
 * UriVariables should be an array of api property exception.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ParamShouldBeTypedException extends Exception implements DomainExceptionInterface
{
    public function __construct(string $param)
    {
        parent::__construct(
            sprintf('param "%s" should be typed.', $param),
        );
    }
}
