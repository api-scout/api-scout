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

namespace ApiScout\OpenApi\Serializer;

use ApiScout\OpenApi\Model\Paths;
use ApiScout\OpenApi\OpenApi;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function is_array;

/**
 * Generates an Open API v3 specification.
 *
 * Inspired by ApiPlatform\OpenApi\Serializer\OpenApiNormalizer
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class OpenApiNormalizer implements NormalizerInterface
{
    public const FORMAT = 'json';

    private const EXTENSION_PROPERTIES_KEY = 'extensionProperties';

    public function __construct(
        private readonly NormalizerInterface $decorated,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $pathsCallback = static fn ($innerObject): array => $innerObject instanceof Paths ? $innerObject->getPaths() : [];
        $context[AbstractObjectNormalizer::PRESERVE_EMPTY_OBJECTS] = true;
        $context[AbstractObjectNormalizer::SKIP_NULL_VALUES] = true;
        $context[AbstractNormalizer::CALLBACKS] = [
            'paths' => $pathsCallback,
        ];

        return $this->recursiveClean(
            /** @phpstan-ignore-next-line $object is mixed */
            $this->decorated->normalize($object, $format, $context),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof OpenApi;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            OpenApi::class => true,
        ];
    }

    private function recursiveClean(array $data): array
    {
        foreach ($data as $key => $value) {
            if (self::EXTENSION_PROPERTIES_KEY === $key) {
                foreach ($data[self::EXTENSION_PROPERTIES_KEY] as $extensionPropertyKey => $extensionPropertyValue) {
                    $data[$extensionPropertyKey] = $extensionPropertyValue;
                }

                continue;
            }

            if (is_array($value)) {
                $data[$key] = $this->recursiveClean($value);
            }
        }

        unset($data[self::EXTENSION_PROPERTIES_KEY]);

        return $data;
    }
}
