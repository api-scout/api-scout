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

namespace ApiScout;

/**
 * Interface to build the Operations.
 *
 * @author Jules Pietri <jules@heahprod.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
interface OperationProviderInterface
{
    public function getCollection(): Operations;

    public function get(string $controllerName): ?Operation;
}
