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

// This file is the entry point to configure your own services.
// Files in the packages/ subdirectory configure your dependencies.

// Put parameters here that don't need to change on each machine where the app is deployed
// https://symfony.com/doc/current/best_practices.html#use-parameters-for-application-configuration
use ApiScout\OpenApi\Factory\OpenApiFactory;
use ApiScout\OpenApi\Factory\OpenApiFactoryInterface;
use ApiScout\OpenApi\JsonSchema\Factory\FilterFactory;
use ApiScout\OpenApi\JsonSchema\Factory\FilterFactoryInterface;
use ApiScout\OpenApi\JsonSchema\Factory\SchemaFactory;
use ApiScout\OpenApi\JsonSchema\Factory\SchemaFactoryInterface;
use ApiScout\OpenApi\Options;
use ApiScout\OpenApi\PaginationOptionsConfigurator;
use ApiScout\OpenApi\Serializer\OpenApiNormalizer;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
    ;

    $services
        ->set('api_scout.openapi.schema_factory', SchemaFactory::class)
    ;

    $services->alias(SchemaFactoryInterface::class, 'api_scout.openapi.schema_factory');

    $services
        ->set('api_scout.openapi.filter_factory', FilterFactory::class)
    ;

    $services->alias(FilterFactoryInterface::class, 'api_scout.openapi.filter_factory');

    $services->set(PaginationOptionsConfigurator::class)
        ->arg('$paginationEnabled', param('api_scout.pagination.enabled'))
        ->arg('$paginationPageParameterName', param('api_scout.pagination.page_parameter_name'))
        ->arg('$paginationItemsPerPage', param('api_scout.pagination.items_per_page'))
        ->arg('$paginationMaximumItemsPerPage', param('api_scout.pagination.maximum_items_per_page'))
    ;

    $services->set(Options::class)
        ->arg('$title', param('api_scout.title'))
        ->arg('$description', param('api_scout.description'))
        ->arg('$version', param('api_scout.version'))
        ->arg('$oAuthEnabled', param('api_scout.oauth.enabled'))
        ->arg('$oAuthType', param('api_scout.oauth.type'))
        ->arg('$oAuthFlow', param('api_scout.oauth.flow'))
        ->arg('$oAuthTokenUrl', param('api_scout.oauth.token_url'))
        ->arg('$oAuthAuthorizationUrl', param('api_scout.oauth.authorization_url'))
        ->arg('$oAuthRefreshUrl', param('api_scout.oauth.refresh_url'))
        ->arg('$oAuthScopes', param('api_scout.oauth.scopes'))
        ->arg('$apiKeys', param('api_scout.openapi.api_keys'))
        ->arg('$contactName', param('api_scout.openapi.contact'))
        ->arg('$contactUrl', param('api_scout.openapi.url'))
        ->arg('$contactEmail', param('api_scout.openapi.email'))
        ->arg('$termsOfService', param('api_scout.openapi.terms_of_service'))
        ->arg('$licenseName', param('api_scout.openapi.license.name'))
        ->arg('$licenseUrl', param('api_scout.openapi.license.url'))
    ;

    $services
        ->set('api_scout.openapi.openapi_factory', OpenApiFactory::class)
        ->arg('$resourceCollection', service(ResourceCollectionFactoryInterface::class))
        ->arg('$schemaFactory', service(SchemaFactoryInterface::class))
        ->arg('$filterFactory', service(FilterFactoryInterface::class))
        ->arg(
            '$paginationOptions',
            expr("service('ApiScout\\\\OpenApi\\\\PaginationOptionsConfigurator').getPaginationOptions()")
        )
        ->arg('$openApiOptions', service(Options::class))
    ;

    $services->alias(OpenApiFactoryInterface::class, 'api_scout.openapi.openapi_factory');

    $services
        ->set('api_scout.openapi.object_normalizer', ObjectNormalizer::class)
        ->private()
        ->arg('$classMetadataFactory', null)
        ->arg('$nameConverter', null)
        ->arg('$propertyAccessor', service(PropertyAccessorInterface::class))
        ->arg('$propertyTypeExtractor', service(PropertyInfoExtractorInterface::class))
        ->call('setSerializer', [service('serializer')])
    ;

    $services
        ->set('api_scout.openapi.normalizer', OpenApiNormalizer::class)
        ->arg('$decorated', service('api_scout.openapi.object_normalizer'))
    ;
};
