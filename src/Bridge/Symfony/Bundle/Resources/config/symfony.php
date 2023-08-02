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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use ApiScout\Bridge\Symfony\EventListener\AddFormatListener;
use ApiScout\Bridge\Symfony\EventListener\ApiLoaderResponseListener;
use ApiScout\Bridge\Symfony\EventListener\EmptyPayloadExceptionListener;
use ApiScout\Bridge\Symfony\EventListener\ExtraAttributeExceptionListener;
use ApiScout\Bridge\Symfony\EventListener\LoaderExceptionListener;
use ApiScout\Bridge\Symfony\EventListener\SerializeResponseListener;
use ApiScout\Bridge\Symfony\EventListener\ValidationExceptionListener;
use ApiScout\Bridge\Symfony\Routing\ApiLoader;
use ApiScout\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use Symfony\Component\HttpKernel\KernelInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services
        ->set(ApiLoader::class)
        ->private()
        ->arg('$kernel', service(KernelInterface::class))
        ->arg('$resourceCollection', service(ResourceCollectionFactoryInterface::class))
        ->arg('$docsEnabled', param('api_scout.enable_docs'))
        ->tag('routing.loader')
    ;

    $services
        ->set(AddFormatListener::class)
        ->private()
        ->arg('$resourceCollectionFactory', service(ResourceCollectionFactoryInterface::class))
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
        ->arg('$resourceCollectionFactory', service(ResourceCollectionFactoryInterface::class))
        ->arg('$paginatorRequestFactory', service(PaginatorRequestFactoryInterface::class))
        ->arg('$serializer', service('serializer'))
        ->arg('$responseItemKey', param('api_scout.response_item_key'))
        ->tag('kernel.event_listener', ['event' => 'kernel.view', 'method' => 'onKernelView', 'priority' => 15])
    ;

    $services
        ->set(ExtraAttributeExceptionListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException', 'priority' => 27])
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
