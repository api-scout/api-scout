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

// This file is the entry point to configure your own services.
// Files in the packages/ subdirectory configure your dependencies.

// Put parameters here that don't need to change on each machine where the app is deployed
// https://symfony.com/doc/current/best_practices.html#use-parameters-for-application-configuration
use ApiScout\Pagination\PaginationMetadata;
use ApiScout\Pagination\PaginationMetadataInterface;
use ApiScout\Pagination\PaginationProvider;
use ApiScout\Pagination\PaginationProviderInterface;
use ApiScout\Pagination\PaginatorRequest;
use ApiScout\Pagination\PaginatorRequestInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services->set('api_scout.pagination.paginator_request_factory', PaginatorRequest::class)
        ->arg(
            '$paginationOptions',
            expr("service('ApiScout\\\\OpenApi\\\\PaginationOptionsConfigurator').getPaginationOptions()")
        )
        ->arg('$requestStack', service('request_stack'))
    ;
    $services->alias(PaginatorRequestInterface::class, 'api_scout.pagination.paginator_request_factory');

    $services->set('api_scout.pagination.pagination_provider', PaginationProvider::class)
        ->arg('$paginatorRequestFactory', service(PaginatorRequestInterface::class))
    ;
    $services->alias(PaginationProviderInterface::class, 'api_scout.pagination.pagination_provider');

    $services->set('api_scout.pagination.pagination_metadata', PaginationMetadata::class)
        ->arg('$urlGenerator', service('router.default'))
        ->arg('$requestStack', service('request_stack'))
    ;
    $services->alias(PaginationMetadataInterface::class, 'api_scout.pagination.pagination_metadata');
};
