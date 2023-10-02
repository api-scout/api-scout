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
 * The Pagination data provider.
 *
 * @author Jérémy Romey <jeremy@free-agent.fr>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PaginationProvider implements PaginationProviderInterface
{
    public function __construct(
        private readonly string $responseItemKey,
        private readonly string $responsePaginationKey,
    ) {
    }

    public function provide(PaginationInterface $pagination, Operation $operation): array
    {
        return [
            $this->responseItemKey => $pagination->getItems(),
            $this->responsePaginationKey => $pagination->getMetadata(),
        ];
    }
}
