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
use ApiScout\Core\Domain\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Core\Domain\Resource\Factory\ResourceCollectionFactoryInterface;
use ApiScout\Core\Domain\Resource\Factory\ResourceFactory;
use ApiScout\Core\Domain\Resource\Factory\ResourceFactoryInterface;
use ApiScout\Core\Infrastructure\Symfony\EventListener\AddFormatListener;
use ApiScout\Core\Infrastructure\Symfony\EventListener\ApiLoaderResponseListener;
use ApiScout\Core\Infrastructure\Symfony\EventListener\EmptyPayloadExceptionListener;
use ApiScout\Core\Infrastructure\Symfony\EventListener\LoaderExceptionListener;
use ApiScout\Core\Infrastructure\Symfony\EventListener\SerializeResponseListener;
use ApiScout\Core\Infrastructure\Symfony\EventListener\ValidationExceptionListener;
use ApiScout\Core\Infrastructure\Symfony\Routing\ApiLoader;
use Symfony\Component\HttpKernel\KernelInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services->set('api_scout.symfony.resource_factory', ResourceFactory::class);
    $services->alias(ResourceFactoryInterface::class, 'api_scout.symfony.resource_factory');

    $services
        ->set(ApiLoader::class)
        ->private()
        ->arg('$kernel', service(KernelInterface::class))
        ->arg('$resourceCollection', service(ResourceCollectionFactoryInterface::class))
        ->arg('$container', service('service_container'))
        ->tag('routing.loader')
    ;

    $services
        ->set(AddFormatListener::class)
        ->private()
        ->arg('$resourceFactory', service(ResourceFactoryInterface::class))
        ->arg('$negotiator', service('api_scout.infrastructure.negotiator'))
        ->tag('kernel.event_listener', ['event' => 'kernel.request', 'method' => 'onKernelRequest', 'priority' => 27])
    ;

    $services
        ->set(ApiLoaderResponseListener::class)
        ->arg('$apiNormalizer', service('api_scout.openapi.normalizer'))
        ->tag('kernel.event_listener', ['event' => 'kernel.view', 'method' => 'onKernelView', 'priority' => 16])
    ;

    $services
        ->set(SerializeResponseListener::class)
        ->arg('$resourceFactory', service(ResourceFactoryInterface::class))
        ->arg('$paginatorRequestFactory', service(PaginatorRequestFactoryInterface::class))
        ->arg('$apiNormalizer', service('api_scout.openapi.normalizer'))
        ->tag('kernel.event_listener', ['event' => 'kernel.view', 'method' => 'onKernelView', 'priority' => 15])
    ;

    $services
        ->set(ValidationExceptionListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException', 'priority' => 27])
    ;

    $services
        ->set(EmptyPayloadExceptionListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException', 'priority' => 27])
    ;

    $services
        ->set(LoaderExceptionListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException', 'priority' => -100])
    ;
};
