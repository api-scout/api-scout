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

use ApiScout\Resource\DirectoryClassesExtractor;
use ApiScout\Resource\Factory\ResourceCollectionFactory;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services
        ->set('api_scout.resource.directory_class_extractor', DirectoryClassesExtractor::class)
        ->arg('$path', param('api_scout.path'))
    ;

    $services
        ->set('api_scout.resource.factory.resource_collection_factory', ResourceCollectionFactory::class)
        ->arg('$directoryClassExtractor', service('api_scout.resource.directory_class_extractor'))
    ;

    $services->alias(ResourceCollectionFactoryInterface::class, 'api_scout.resource.factory.resource_collection_factory');
};
