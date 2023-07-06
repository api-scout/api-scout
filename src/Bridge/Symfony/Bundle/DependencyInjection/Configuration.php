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

namespace ApiScout\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use function is_array;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('api_scout');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->beforeNormalization()
            ->ifTrue(static fn ($v) => ($v['enable_swagger'] ?? null) === false)
            ->then(static function ($v) {
                $v['swagger']['versions'] = [];

                return $v;
            })
            ->end()
            ->children()
            ->scalarNode('title')
            ->info('The title of the API.')
            ->cannotBeEmpty()
            ->defaultValue('')
            ->end()
            ->scalarNode('description')
            ->info('The description of the API.')
            ->cannotBeEmpty()
            ->defaultValue('')
            ->end()
            ->scalarNode('version')
            ->info('The version of the API.')
            ->cannotBeEmpty()
            ->defaultValue('0.0.0')
            ->end()
            ->scalarNode('path')
            ->defaultValue('')
            ->cannotBeEmpty()
            ->info('Controllers path.')
            ->end()

//			->arrayNode('mapping')
//				->addDefaultsIfNotSet()
//				->children()
//					->arrayNode('paths')
//						->prototype('scalar')->end()
//					->end()
//				->end()
//			->end()
//			->arrayNode('resource_class_directories')
//				->prototype('scalar')->end()
//			->end()
        ;

        $this->addSwaggerUiContextSection($rootNode);
        $this->addPaginationSection($rootNode);
        $this->addOpenApiSection($rootNode);
        $this->addOAuthSection($rootNode);

        return $treeBuilder;
    }

    private function addPaginationSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
            ->arrayNode('pagination')
            ->canBeDisabled()
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('page_parameter_name')->defaultValue('page')->cannotBeEmpty()->info('The default name of the parameter handling the page number.')->end()
            ->integerNode('items_per_page')->defaultValue(10)->info('The minimum number of items per page.')->end()
            ->integerNode('maximum_items_per_page')->defaultValue(50)->info('The name of the query parameter to enable or disable partial pagination.')->end()
            ->end()
            ->end()
        ;
    }

    private function addOpenApiSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
            ->arrayNode('openapi')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('contact')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('name')->defaultNull()->info('The identifying name of the contact person/organization.')->end()
            ->scalarNode('url')->defaultNull()->info('The URL pointing to the contact information. MUST be in the format of a URL.')->end()
            ->scalarNode('email')->defaultNull()->info('The email address of the contact person/organization. MUST be in the format of an email address.')->end()
            ->end()
            ->end()
            ->scalarNode('terms_of_service')->defaultNull()->info('A URL to the Terms of Service for the API. MUST be in the format of a URL.')->end()
            ->arrayNode('license')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('name')->defaultNull()->info('The license name used for the API.')->end()
            ->scalarNode('url')->defaultNull()->info('URL to the license used for the API. MUST be in the format of a URL.')->end()
            ->end()
            ->end()
            ->arrayNode('api_keys')
            ->useAttributeAsKey('key')
            ->validate()
            ->ifTrue(static fn ($v): bool => (bool) array_filter(array_keys($v), fn ($item) => !preg_match('/^[a-zA-Z0-9._-]+$/', $item)))
            ->thenInvalid('The api keys "key" is not valid according to the pattern enforced by OpenAPI 3.1 ^[a-zA-Z0-9._-]+$.')
            ->end()
            ->prototype('array')
            ->children()
            ->scalarNode('name')
            ->info('The name of the header or query parameter containing the api key.')
            ->end()
            ->enumNode('type')
            ->info('Whether the api key should be a query parameter or a header.')
            ->values(['query', 'header'])
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;
    }

    private function addOAuthSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
            ->arrayNode('oauth')
            ->canBeEnabled()
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('clientId')->defaultValue('')->info('The oauth client id.')->end()
            ->scalarNode('clientSecret')
            ->defaultValue('')
            ->info('The OAuth client secret. Never use this parameter in your production environment. It exposes crucial security information. This feature is intended for dev/test environments only. Enable "oauth.pkce" instead')
            ->end()
            ->booleanNode('pkce')->defaultFalse()->info('Enable the oauth PKCE.')->end()
            ->scalarNode('type')->defaultValue('oauth2')->info('The oauth type.')->end()
            ->scalarNode('flow')->defaultValue('application')->info('The oauth flow grant type.')->end()
            ->scalarNode('token_url')->defaultValue('')->info('The oauth token url.')->end()
            ->scalarNode('authorization_url')->defaultValue('')->info('The oauth authentication url.')->end()
            ->scalarNode('refresh_url')->defaultValue('')->info('The oauth refresh url.')->end()
            ->arrayNode('scopes')
            ->prototype('scalar')->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;
    }

    private function addSwaggerUiContextSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
            ->booleanNode('show_webby')->defaultTrue()->info('If true, show Webby on the documentation page')->end()
            ->scalarNode('asset_package')->defaultNull()->info('Specify an asset package name to use.')->end()
            ->booleanNode('enable_swagger')->defaultTrue()->info('Enable the Swagger documentation and export.')->end()
            ->booleanNode('enable_swagger_ui')->defaultValue(class_exists(TwigBundle::class))->info('Enable Swagger UI')->end()
            ->booleanNode('enable_re_doc')->defaultValue(class_exists(TwigBundle::class))->info('Enable ReDoc')->end()
            ->variableNode('swagger_ui_extra_configuration')
            ->defaultValue([])
            ->validate()
            ->ifTrue(static fn ($v): bool => false === is_array($v))
            ->thenInvalid('The swagger_ui_extra_configuration parameter must be an array.')
            ->end()
            ->info('To pass extra configuration to Swagger UI, like docExpansion or filter.')
            ->end()
            ->end()
        ;
    }
}
