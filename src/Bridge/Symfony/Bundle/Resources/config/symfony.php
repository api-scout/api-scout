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
use ApiScout\Bridge\Symfony\EventListener\CustomExceptionListener;
use ApiScout\Bridge\Symfony\EventListener\OperationRequestListener;
use ApiScout\Bridge\Symfony\EventListener\PayloadValidationExceptionListener;
use ApiScout\Bridge\Symfony\EventListener\SerializeResponseListener;
use ApiScout\Bridge\Symfony\Routing\ApiLoader;
use ApiScout\OperationProviderInterface;
use ApiScout\Pagination\PaginationProviderInterface;
use Symfony\Component\HttpKernel\KernelInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services
        ->set(ApiLoader::class)
        ->private()
        ->arg('$kernel', service(KernelInterface::class))
        ->arg('$resourceCollection', service(OperationProviderInterface::class))
        ->arg('$docsEnabled', param('api_scout.enable_docs'))
        ->tag('routing.loader')
    ;

    $services
        ->set(OperationRequestListener::class)
        ->arg('$resourceCollectionFactory', service(OperationProviderInterface::class))
        ->tag('kernel.event_subscriber')
    ;

    $services
        ->set(AddFormatListener::class)
        ->arg('$negotiator', service('api_scout.infrastructure.negotiator'))
        ->tag('kernel.event_listener', ['event' => 'kernel.request', 'method' => 'onKernelRequest', 'priority' => 27])
    ;

    $services
        ->set(ApiLoaderResponseListener::class)
        ->arg('$apiNormalizer', service('api_scout.openapi.normalizer'))
        ->tag('kernel.event_listener', ['event' => 'kernel.view', 'method' => 'onKernelView', 'priority' => 16])
    ;

    $services->set(SerializeResponseListener::class)
        ->arg('$paginationProvider', service(PaginationProviderInterface::class))
        ->arg('$responseSerializer', service('api_scout.response_serializer'))
        ->arg('$prepareResponse', service('api_scout.api.prepare_response'))
        ->tag('kernel.event_listener', ['event' => 'kernel.view', 'method' => 'onKernelView', 'priority' => 15])
    ;

    $services->set('api_scout.symfony.custom_exception_listener', CustomExceptionListener::class)
        ->arg('$exceptionsToStatuses', param('api_scout.exception_to_status'))
        ->tag('kernel.event_subscriber')
    ;

    $services
        ->set('api_scout.payload_validation_exception_listener', PayloadValidationExceptionListener::class)
        ->arg('$exceptionsToStatuses', param('api_scout.exception_to_status'))
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException', 'priority' => 27])
    ;
};
