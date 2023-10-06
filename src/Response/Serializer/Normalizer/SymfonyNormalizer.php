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

namespace ApiScout\Response\Serializer\Normalizer;

use ApiScout\Operation;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface as SymfonyNormalizerInterface;

final class SymfonyNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly SymfonyNormalizerInterface $normalizer
    ) {
    }

    public function normalize(mixed $object, Operation $operation): array
    {
        /** @phpstan-ignore-next-line Will always return an array */
        return $this->normalizer->normalize($object, context: $operation->getNormalizationContext());
    }
}
