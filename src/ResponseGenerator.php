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

use ApiScout\Pagination\PaginationInterface;
use ApiScout\Pagination\PaginationMetadataInterface;

use function is_object;

/**
 * The ApiScout Response preparation Interface.
 *
 * @author Jérémy Romey <jeremy@free-agent.fr>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ResponseGenerator implements ResponseGeneratorInterface
{
    public function __construct(
        private readonly PaginationMetadataInterface $paginationMetadata,
        private readonly string $responseItemKey,
        private readonly string $responsePaginationKey,
    ) {
    }

    public function generate(object|array $data, Operation $operation): array
    {
        if ($data instanceof PaginationInterface) {
            return [
                $this->responseItemKey => $data->getItems(),
                $this->responsePaginationKey => $this->paginationMetadata->getMetadata($data, $operation),
            ];
        }

        if (is_object($data)) {
            return [$this->responseItemKey => $data];
        }

        return $data;
    }
}
