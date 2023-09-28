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
use ApiScout\Pagination\Factory\PaginatorRequestFactory;
use ApiScout\Pagination\Factory\PaginatorRequestFactoryInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services
        ->set('api_scout.pagination.paginator_request_factory', PaginatorRequestFactory::class)
        ->arg(
            '$paginationOptions',
            expr("service('ApiScout\\\\OpenApi\\\\PaginationOptionsConfigurator').getPaginationOptions()")
        )
    ;
    $services
        ->alias(PaginatorRequestFactoryInterface::class, 'api_scout.pagination.paginator_request_factory')
    ;
};
