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
use ApiScout\Bridge\Symfony\Bundle\SwaggerUi\SwaggerJsonAction;
use ApiScout\Bridge\Symfony\Bundle\SwaggerUi\SwaggerUiAction;
use ApiScout\Bridge\Symfony\Bundle\SwaggerUi\SwaggerUiContext;
use ApiScout\OpenApi\Factory\OpenApiFactoryInterface;
use ApiScout\OpenApi\Options;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment as TwigEnvironment;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services
        ->set(SwaggerUiContext::class)
        ->arg('$swaggerUiEnabled', param('api_scout.enable_swagger_ui'))
        ->arg('$reDocEnabled', param('api_scout.enable_re_doc'))
    ;

    $services
        ->set('api_scout.swagger_ui.action', SwaggerUiAction::class)
        ->arg('$openApiFactory', service(OpenApiFactoryInterface::class))
        ->arg('$swaggerUiContext', service(SwaggerUiContext::class))
        ->arg('$urlGenerator', service(UrlGeneratorInterface::class))
        ->arg('$apiNormalizer', service('api_scout.openapi.normalizer'))
        ->arg('$openApiOptions', service(Options::class))
        ->arg('$twig', service(TwigEnvironment::class)->nullOnInvalid())
        ->tag('controller.service_arguments')
    ;

    $services
        ->set('api_scout.swagger_json.action', SwaggerJsonAction::class)
        ->arg('$openApiFactory', service(OpenApiFactoryInterface::class))
        ->tag('controller.service_arguments')
    ;
};
