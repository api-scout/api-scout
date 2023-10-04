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

namespace ApiScout\Response\Pagination;

use ApiScout\Operation;

/**
 * The ApiScout Pagination Metadata interface.
 *
 * @author Jérémy Romey <jeremy@free-agent.fr>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
interface PaginationMetadataInterface
{
    public function getMetadata(PaginationInterface $pagination, Operation $operation): array;
}
