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

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

use function count;

final class Operations implements IteratorAggregate, Countable
{
    /**
     * @param array<string, Operation> $operations
     */
    public function __construct(private readonly array $operations = [])
    {
    }

    /**
     * @return array<string, Operation>
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    public function getOperation(string $routeName): Operation
    {
        return $this->operations[$routeName];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->operations);
    }

    public function count(): int
    {
        return count($this->operations);
    }
}
