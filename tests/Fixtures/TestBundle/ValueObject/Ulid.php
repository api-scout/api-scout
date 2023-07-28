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

namespace ApiScout\Tests\Fixtures\TestBundle\ValueObject;

use ApiScout\Tests\Fixtures\TestBundle\Assert\Assertion;
use JsonSerializable;
use Symfony\Component\Uid\Ulid as SymfonyUlid;

final class Ulid implements JsonSerializable
{
    private SymfonyUlid $value;

    private function __construct(string $value)
    {
        Assertion::notBlank($value, 'This value should not be blank.');

        $this->value = new SymfonyUlid($value);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function create(): self
    {
        return new self((new SymfonyUlid())->toBase32());
    }

    public function value(): string
    {
        return $this->toBase32();
    }

    public function toBase32(): string
    {
        return $this->value->toBase32();
    }

    public function equals(self $ulid): bool
    {
        return $this->value() === $ulid->value();
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    /**
     * @param array<string> $stringUlids
     *
     * @return array<Ulid>
     */
    public static function fromArray(array $stringUlids): array
    {
        $ulids = [];
        foreach ($stringUlids as $stringUlid) {
            $ulids[] = self::fromString($stringUlid);
        }

        return $ulids;
    }
}
