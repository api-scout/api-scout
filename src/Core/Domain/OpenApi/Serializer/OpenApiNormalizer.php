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

namespace ApiScout\Core\Domain\OpenApi\Serializer;

use ApiScout\Core\Domain\OpenApi\Model\Paths;
use ApiScout\Core\Domain\OpenApi\OpenApi;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function is_array;

/**
 * Generates an OpenAPI v3 specification.
 */
// #[AsDecorator(decorates: NormalizerInterface::class)]
// #[AutoconfigureTag('serializer.normalizer', ['priority' => '-795'])]
final class OpenApiNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public const FORMAT = 'json';
    private const EXTENSION_PROPERTIES_KEY = 'extensionProperties';

    public function __construct(
        //        private readonly ObjectNormalizer $decorated
        private readonly NormalizerInterface $decorated
    ) {
        //      dd($decorated);
    }

    /**
     * {@inheritdoc}
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
            $this->decorated->normalize($object, $format, $context)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof OpenApi;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    private function recursiveClean(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($key === self::EXTENSION_PROPERTIES_KEY) {
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
