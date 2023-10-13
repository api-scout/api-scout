<?php

namespace ApiScout\Resource;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

final readonly class OperationCacheBuilder implements CacheWarmerInterface
//final readonly class OperationCacheBuilder implements WarmableInterface
{
    public function __construct(
        private OperationProviderInterface $operationProvider,
        private iterable $controllerWithOperationsClasses
    ) {
    }

    public function isOptional(): bool
    {
        return false;
    }

    public function warmUp(string $cacheDir): array
    {
        $classNames = [];

        foreach($this->controllerWithOperationsClasses as $handler) {
            $classNames[] = get_class($handler);
        }

        $this->operationProvider->getCollection($classNames);

        return $classNames;
    }
}
