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

namespace ApiScout\Bridge\Symfony\Routing;

use ApiScout\Exception\ResourceClassNotFoundException;
use ApiScout\HttpOperation;
use ApiScout\OperationProviderInterface;
use ApiScout\Operations;
use LogicException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class ApiLoader extends Loader
{
    private PhpFileLoader $fileLoader;

    public function __construct(
        KernelInterface $kernel,
        private readonly OperationProviderInterface $resourceCollection,
        private readonly bool $docsEnabled,
    ) {
        parent::__construct($kernel->getEnvironment());

        /** @var array<string>|string $paths */
        $paths = $kernel->locateResource('@ApiScoutBundle/Resources/config/routes');
        $this->fileLoader = new PhpFileLoader(new FileLocator($paths));
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        /**
         * @var Operations<HttpOperation> $operations
         */
        $operations = $this->resourceCollection->getCollection();

        $routeCollection = new RouteCollection();

        $this->loadExternalFiles($routeCollection);

        foreach ($operations as $operation) {
            if (!class_exists($operation->getController())) {
                throw new ResourceClassNotFoundException($operation->getController());
            }

            $controller = $operation->getControllerMethod() === '__invoke'
                ? $operation->getController()
                : $operation->getController().'::'.$operation->getControllerMethod();

            if ($operation->getName() === null) {
                throw new LogicException('Operation name should have been initialized before hand.');
            }

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

    private function loadExternalFiles(RouteCollection $routeCollection): void
    {
        if ($this->docsEnabled) {
            $routeCollection->addCollection($this->fileLoader->load('swagger.php'));
        }
    }
}
