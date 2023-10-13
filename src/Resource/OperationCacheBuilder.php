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

namespace ApiScout\Resource;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

final readonly class OperationCacheBuilder implements CacheWarmerInterface
    // final readonly class OperationCacheBuilder implements WarmableInterface
{
    public function __construct(
        private OperationProviderInterface $operationProvider,
    ) {
    }

    public function isOptional(): bool
    {
        return false;
    }

    public function warmUp(string $cacheDir): array
    {
        $this->operationProvider->getCollection();

        return [];
    }
}
