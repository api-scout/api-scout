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
use ApiScout\Response\Pagination\QueryInput\PaginationQueryInputInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePagination;
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
        private readonly PaginationMetadataInterface $paginationMetadata,
        private readonly string $responseItemKey,
        private readonly string $responsePaginationKey,
    ) {
    }

    public function provide(
        mixed $data,
        Operation $operation,
        PaginationQueryInputInterface $paginationQueryInput,
    ): array {
        $pagination = $this->getPagination($data, $operation, $paginationQueryInput);

        return [
            $this->responseItemKey => $pagination->getItems(),
            $this->responsePaginationKey => $this->paginationMetadata->getMetadata(
                $pagination,
                $operation,
            ),
        ];
    }

    private function getPagination(
        mixed $data,
        Operation $operation,
        PaginationQueryInputInterface $paginationQueryInput,
    ): PaginationInterface {
        if ($data instanceof PaginationInterface) {
            return $data;
        }

        if (class_exists(DoctrinePagination::class) && $data instanceof DoctrinePagination) {
            return $this->getDoctrinePagination($data, $operation, $paginationQueryInput);
        }

        if (!is_iterable($data)) {
            throw new LogicException('$data from Collection Operation should be iterable.');
        }

        return new Pagination(
            $data,
            $paginationQueryInput->getPage(),
            $paginationQueryInput->getItemsPerPage(),
            null,
        );
    }

    private function getDoctrinePagination(
        DoctrinePagination $data,
        Operation $operation,
        PaginationQueryInputInterface $paginationQueryInput,
    ): PaginationInterface {
        return new Pagination(
            $data->getIterator(),
            $paginationQueryInput->getPage(),
            $paginationQueryInput->getItemsPerPage(),
            $data->count(),
        );
    }
}
