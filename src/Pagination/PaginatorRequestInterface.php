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

namespace ApiScout\Pagination;

use ApiScout\Operation;

/**
 * The Paginator Request Factory interface.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
interface PaginatorRequestInterface
{
    public function getCurrentPage(): int;

    public function getItemsPerPage(Operation $operation): int;
}
