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
use LogicException;

/**
 * The Pagination data provider.
 *
 * @author Jérémy Romey <jeremy@free-agent.fr>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PaginationProvider implements PaginationProviderInterface
{
    public function __construct(
        private readonly PaginatorRequestInterface $paginatorRequestFactory,
        private readonly PaginationMetadataInterface $paginationMetadata,
        private readonly string $responseItemKey,
        private readonly string $responsePaginationKey,
    ) {
    }

    public function provide(mixed $data, Operation $operation): array
    {
        $pagination = $this->getPagination($data, $operation);

        return [
            $this->responseItemKey => $pagination->getItems(),
            $this->responsePaginationKey => $this->paginationMetadata->getMetadata($pagination, $operation),
        ];
    }

    private function getPagination(mixed $data, Operation $operation): PaginationInterface
    {
        if ($data instanceof PaginationInterface) {
            return $data;
        }

        if (!is_iterable($data)) {
            throw new LogicException('$data from Collection Operation should be iterable.');
        }

        return new Pagination(
            $data,
            $this->paginatorRequestFactory->getCurrentPage(),
            $this->paginatorRequestFactory->getItemsPerPage($operation),
            null
        );
    }
}
