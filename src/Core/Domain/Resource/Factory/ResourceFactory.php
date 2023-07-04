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

use ApiScout\Core\Domain\Operation;
use ApiScout\Core\Domain\Resource\OperationBuilderTrait;
use ReflectionClass;
use RuntimeException;

final class ResourceFactory implements ResourceFactoryInterface
{
    use OperationBuilderTrait;

    /**
     * @param class-string $controller
     */
    public function initializeOperation(string $controller, string $route): Operation
    {
        $reflectionClass = new ReflectionClass($controller);

        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->class !== $controller) {
                // We only want the method of the current class. This line avoid reading inherited classes
                break;
            }

            if ($this->isOperationResource($method)) {
                $operation = $this->buildOperationFromMethod($method, $controller);
                if ($operation->getName() === $route) {
                    return $operation;
                }
            }
        }

        throw new RuntimeException('Controller with route name was not found.');
    }
}
