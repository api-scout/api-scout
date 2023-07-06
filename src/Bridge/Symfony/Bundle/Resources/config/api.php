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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

// This file is the entry point to configure your own services.
// Files in the packages/ subdirectory configure your dependencies.

// Put parameters here that don't need to change on each machine where the app is deployed
// https://symfony.com/doc/current/best_practices.html#use-parameters-for-application-configuration
use ApiScout\Resource\Factory\ResourceCollectionFactory;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use Negotiation\Negotiator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services
        ->set('api_scout.resource.resource_collection_factory', ResourceCollectionFactory::class)
        ->arg('$path', param('api_scout.path'))
    ;
    $services->alias(ResourceCollectionFactoryInterface::class, 'api_scout.resource.resource_collection_factory');

    $services
        ->set('api_scout.infrastructure.negotiator', Negotiator::class)
    ;
};
