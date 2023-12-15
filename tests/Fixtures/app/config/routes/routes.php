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

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import(
        resource: [
            'path' => __DIR__.'/../../../TestBundle/Controller/',
            'namespace' => 'ApiScout\Tests\Fixtures\TestBundle\Controller',
        ],
        type: 'attribute',
    );

    $routes
        ->add('api_scout_swagger_ui', '/api/docs.{_format}')
        ->controller('api_scout.swagger_ui.action')
        ->format('html')
        ->methods(['GET'])
    ;
};
