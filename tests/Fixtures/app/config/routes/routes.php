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

use ApiScout\Bridge\Symfony\Bundle\SwaggerUi\SwaggerJsonAction;
use ApiScout\Bridge\Symfony\Bundle\SwaggerUi\SwaggerUiAction;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import(
        resource: '.',
        type: 'api_scout'
    );

    $routes
        ->add('api_scout_swagger_ui', '/api/docs')
        ->controller(SwaggerUiAction::class)
        ->methods(['GET'])
    ;

    $routes
        ->add('api_scout_swagger_json', '/api/docs.json')
        ->controller(SwaggerJsonAction::class)
        ->methods(['GET'])
    ;
};
