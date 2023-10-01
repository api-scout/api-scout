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

namespace ApiScout\Pagination\Factory;

use ApiScout\Operation;
use Symfony\Component\HttpFoundation\Request;

interface PaginatorRequestFactoryInterface
{
    public function getCurrentPage(Request $request): int;

    public function getItemsPerPage(Operation $operation): int;
}
