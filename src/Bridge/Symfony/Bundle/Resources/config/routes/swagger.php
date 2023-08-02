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
    $routes
        ->add('api_scout_swagger_ui', '/api/docs')
        ->controller('api_scout.swagger_ui.action')
        ->methods(['GET'])
    ;

    $routes
        ->add('api_scout_swagger_json', '/api/docs.json')
        ->controller('api_scout.swagger_json.action')
        ->methods(['GET'])
    ;
};
