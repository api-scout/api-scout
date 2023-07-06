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
use ApiScout\Tests\Behat\Symfony\HttpClient\Client;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->bind('$httpTestClient', service(Client::class))
    ;

    $services
        ->load('ApiScout\\Tests\\Behat\\', __DIR__.'/../../../../tests/Behat/*')
        ->load('ApiScout\\Tests\\Fixtures\\TestBundle\\', __DIR__.'/../../../../tests/Fixtures/TestBundle')
    ;

    $services->set('app.api_loader.kernel_browser', KernelBrowser::class);

    $services->set(Client::class)
        ->arg('$kernelBrowser', service('app.api_loader.kernel_browser'))
    ;

    $services
        ->set('app.api_loader.controller')
        ->synthetic()
        ->load(
            'ApiScout\\Tests\\Fixtures\\TestBundle\\Controller\\',
            __DIR__.'/../../TestBundle/Controller/**/*Controller.php'
        )
        ->tag('controller.service_arguments')
    ;

    $services
        ->set('api_scout.resource.resource_collection_factory', ResourceCollectionFactory::class)
        ->arg('$path', __DIR__.'/../../TestBundle/Controller')
    ;

    $services->alias(ResourceCollectionFactoryInterface::class, 'api_scout.resource.resource_collection_factory');
};
