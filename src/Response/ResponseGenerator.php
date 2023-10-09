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

namespace ApiScout\Response;

use ApiScout\Attribute\CollectionOperationInterface;
use ApiScout\Operation;

/**
 * The ApiScout Response preparation Interface.
 *
 * @author Jérémy Romey <jeremy@free-agent.fr>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ResponseGenerator implements ResponseGeneratorInterface
{
    public function __construct(
        private readonly string $responseItemKey,
    ) {
    }

    public function generate(array $data, Operation $operation): array
    {
        if ($operation instanceof CollectionOperationInterface && $operation->isPaginationEnabled()) {
            return $data;
        }

        return [$this->responseItemKey => $data];
    }
}
