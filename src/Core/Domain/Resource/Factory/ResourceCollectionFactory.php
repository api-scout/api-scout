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

namespace ApiScout\Core\Domain\Resource\Factory;

use ApiScout\Core\Domain\Exception\ResourceClassNotFoundException;
use ApiScout\Core\Domain\Operations;
use ApiScout\Core\Domain\Resource\OperationBuilderTrait;
use ApiScout\Core\Domain\Utils\DirectoryClassExtractor;
use ReflectionClass;

final class ResourceCollectionFactory implements ResourceCollectionFactoryInterface
{
    use OperationBuilderTrait;

    public function __construct(
        private readonly string $path
    ) {
    }

    public function create(): Operations
    {
        $classes = DirectoryClassExtractor::extract($this->path);

        $operations = new Operations();

        foreach ($classes as $controller) {
            if (!class_exists($controller)) {
                throw new ResourceClassNotFoundException($controller);
            }

            $reflectionClass = new ReflectionClass($controller);

            foreach ($reflectionClass->getMethods() as $method) {
                if ($method->class !== $controller) {
                    // We only want the method of the current class. This line avoid reading inherited classes
                    break;
                }

                if ($this->isOperationResource($method)) {
                    $operation = $this->buildOperationFromMethod($method, $controller);
                    $operations->add($operation->getName(), $operation);
                }
            }
        }

        return $operations;
    }
}
