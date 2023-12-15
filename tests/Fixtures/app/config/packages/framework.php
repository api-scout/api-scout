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

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Session\SessionFactory;

return static function (ContainerConfigurator $container): void {
    $array = [
        'secret' => 'marvin.fr',
        'validation' => [],
        'serializer' => [],
        'test' => null,
        'session' => class_exists(SessionFactory::class) ? ['storage_factory_id' => 'session.storage.factory.mock_file'] : ['storage_id' => 'session.storage.mock_file'],
        'profiler' => [
            'enabled' => true,
            'collect' => false,
        ],
        'router' => ['utf8' => true],
        'http_method_override' => false,
    ];

    $container->extension('framework', $array);
};
