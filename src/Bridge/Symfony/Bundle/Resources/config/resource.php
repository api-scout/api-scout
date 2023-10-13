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

use ApiScout\Resource\DirectoryClassesExtractor;
use ApiScout\Resource\OperationCacheBuilder;
use ApiScout\Resource\OperationProvider;
use ApiScout\Resource\OperationProviderInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services->set('api_scout.resource.directory_class_extractor', DirectoryClassesExtractor::class)
        ->arg('$paths', param('api_scout.mapping.paths'))
    ;

    $services->set('api_scout.resource.factory.resource_collection_factory', OperationProvider::class)
        ->arg('$directoryClassExtractor', service('api_scout.resource.directory_class_extractor'))
        ->arg('$cache', service('cache.system'))
    ;

    $services->alias(OperationProviderInterface::class, 'api_scout.resource.factory.resource_collection_factory');


    $services->set('api_scout.resource.operation_builder', OperationCacheBuilder::class)
        ->arg('$operationProvider', service(OperationProviderInterface::class))
        ->arg('$controllerWithOperationsClasses', tagged_iterator('api_scout.controller_operations'))
        ->Tag('kernel.cache_warmer', ['priority' => 0])
    ;
};
