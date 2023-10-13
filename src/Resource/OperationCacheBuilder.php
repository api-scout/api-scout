<?php

namespace ApiScout\Resource;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

//final readonly class OperationBuilder implements CacheWarmerInterface
final readonly class OperationBuilder implements WarmableInterface
{
    public function __construct(
        private OperationProviderInterface $operationProvider
    ) {
    }

//    public function isOptional()
//    {
//        // TODO: Implement isOptional() method.
//    }
//
    public function warmUp(string $cacheDir)
    {
        // TODO: Implement warmUp() method.
    }
}
