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

namespace ApiScout\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

final class SymfonyResponseSerializer implements ResponseSerializerInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly string $responseItemKey,
    ) {
    }

    /**
     * Serializes data in the appropriate format.
     *
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     */
    public function serialize(mixed $data, array $context): string
    {
        return $this->serializer->serialize(
            data: [$this->responseItemKey => $data],
            format: 'json',
            context: $context
        );
    }
}
