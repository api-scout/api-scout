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
 * Resource class not found exception.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ResourceClassNotFoundException extends Exception implements DomainExceptionInterface
{
    public function __construct(string $class)
    {
        parent::__construct(
            sprintf('Resource class "%s" not found.', $class),
        );
    }
}
