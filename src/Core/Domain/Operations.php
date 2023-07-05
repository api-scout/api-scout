<?php

declare(strict_types=1);

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiScout\Core\Domain;

use Countable;
use Generator;
use IteratorAggregate;
use Traversable;

use function count;

final class Operations implements IteratorAggregate, Countable
{
    /**
     * @var array<string, Operation>
     */
    private array $operations = [];

    /**
     * @param array<string, Operation> $operations
     */
    public function __construct(array $operations = [])
    {
        foreach ($operations as $operationName => $operation) {
            $this->operations[$operationName] = $operation;
        }
    }

    /**
     * @return array<string, Operation>
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    public function add(string $key, Operation $value): self
    {
        $this->operations[$key] = $value;

        return $this;
    }

    public function getOperation(string $routeName): Operation
    {
        return $this->operations[$routeName];
    }

    public function getIterator(): Traversable
    {
        return (function (): Generator {
            foreach ($this->operations as [$operationName, $operation]) {
                yield $operationName => $operation;
            }
        })();
    }

    public function count(): int
    {
        return count($this->operations);
    }
}
