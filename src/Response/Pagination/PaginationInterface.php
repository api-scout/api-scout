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

/**
 * The ApiScout Pagination Interface.
 *
 * @author Jérémy Romey <jeremy@free-agent.fr>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
interface PaginationInterface
{
    public function getItems(): iterable;

    public function getMetadata(): array;

    public function getCurrentPage(): int;

    public function getItemsPerPage(): int;

    public function getTotalItems(): ?int;
}
