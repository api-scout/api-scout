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

use ApiScout\Pagination\PaginationMetadataInterface;
use ApiScout\ResponseGenerator;
use ApiScout\ResponseGeneratorInterface;
use ApiScout\Serializer\SymfonyResponseSerializer;
use Negotiation\Negotiator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services->set('api_scout.infrastructure.negotiator', Negotiator::class);

    $services->set('api_scout.response_serializer', SymfonyResponseSerializer::class)
        ->arg('$serializer', service('serializer'))
    ;

    $services->set('api_scout.api.prepare_response', ResponseGenerator::class)
        ->arg('$paginationMetadata', service(PaginationMetadataInterface::class))
        ->arg('$responseItemKey', param('api_scout.response_item_key'))
        ->arg('$responsePaginationKey', param('api_scout.response_pagination_key'))
    ;
    $services->alias(ResponseGeneratorInterface::class, 'api_scout.api.prepare_response');
};
