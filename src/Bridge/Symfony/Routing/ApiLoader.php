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

namespace ApiScout\Bridge\Symfony\Routing;

use ApiScout\Exception\ResourceClassNotFoundException;
use ApiScout\Exception\RuntimeException;
use ApiScout\HttpOperation;
use ApiScout\Operations;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class ApiLoader extends Loader
{
    public function __construct(
        KernelInterface $kernel,
        private readonly ResourceCollectionFactoryInterface $resourceCollection,
        private readonly ContainerInterface $container
    ) {
        parent::__construct($kernel->getEnvironment());
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        /**
         * @var Operations<HttpOperation> $operations
         */
        $operations = $this->resourceCollection->create();

        $routeCollection = new RouteCollection();
        foreach ($operations->getOperations() as $operation) {
            if (!class_exists($operation->getController())) {
                throw new ResourceClassNotFoundException($operation->getController());
            }

            $controller = $operation->getControllerMethod() === '__invoke'
                ? $operation->getController()
                : $operation->getController().'::'.$operation->getControllerMethod();

            $route = new Route(
                $operation->getPath(),
                [
                    '_controller' => $controller,
                    '_controller_class' => $operation->getController(),
                    '_route_name' => $operation->getName(),
                ],
                $operation->getRequirements(),
                $operation->getOptions(),
                $operation->getHost() ?? '',
                $operation->getSchemes(),
                [$operation->getMethod()],
                $operation->getCondition() ?? ''
            );

            $routeCollection->add($operation->getName(), $route);
        }

        return $routeCollection;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $type === 'api_scout';
    }
}
