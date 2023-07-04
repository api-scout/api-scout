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

namespace ApiScout\Tests\Fixtures\TestBundle\ValueObject;

use ApiScout\Tests\Fixtures\TestBundle\Assert\Assertion;
use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use LogicException;
use Traversable;

use function count;

/**
 * @template ITEM of object
 *
 * @implements IteratorAggregate<ITEM>
 */
abstract class Collection implements IteratorAggregate, Countable
{
    /**
     * @var class-string<object>
     */
    protected static string $type;

    /**
     * @var array<ITEM>
     */
    protected array $items = [];

    final public function __construct()
    {
        if (!class_exists(static::$type)) {
            throw new LogicException('Invalid item type class "'.static::$type.'".');
        }
    }

    /**
     * @return Traversable<ITEM>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    /**
     * @param ITEM $itemToAdd
     */
    public function add(object $itemToAdd): void
    {
        Assertion::isInstanceOf($itemToAdd, static::$type);

        $this->items[] = $itemToAdd;
    }

    /**
     * @param ITEM $itemToRemove
     */
    public function remove(object $itemToRemove): void
    {
        Assertion::isInstanceOf($itemToRemove, static::$type);

        foreach ($this->items as $itemKey => $item) {
            if ($item === $itemToRemove) {
                unset($this->items[$itemKey]);
            }
        }
    }

    /**
     * @param array<ITEM> $items
     *
     * @return static
     */
    public static function createFromArray(array $items): self
    {
        $collection = new static();

        array_walk($items, static function ($item) use ($collection): void {
            $collection->add($item);
        });

        return $collection;
    }

    /**
     * @return static
     */
    public function filter(Closure $c): self
    {
        return static::createFromArray(array_filter(
            $this->items,
            $c,
            \ARRAY_FILTER_USE_BOTH
        ));
    }

    /**
     * @return array<ITEM>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return ITEM
     */
    public function first(): ?object
    {
        $first = reset($this->items);

        return $first !== false ? $first : null;
    }

    /**
     * @return ITEM
     */
    public function last(): ?object
    {
        $last = end($this->items);

        return $last !== false ? $last : null;
    }

    public function has(object $object): bool
    {
        /** @phpstan-ignore-next-line  */
        return $this->filter(static fn (object $objectInCollection) => $object->getId()->equals($objectInCollection->getId()))->count() !== 0;
    }
}
