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

namespace ApiScout\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

use function is_array;

/**
 * The configuration of the bundle.
 *
 * Inspired by ApiPlatform\Symfony\Bundle\DependencyInjection\Configuration:
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Baptiste Meyer <baptiste.meyer@gmail.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
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
            ->scalarNode('response_item_key')
            ->info('The value for your response key root.')
            ->cannotBeEmpty()
            ->defaultValue('data')
            ->end()
            ->scalarNode('response_pagination_key')
            ->info('The value for your response pagination metadata.')
            ->cannotBeEmpty()
            ->defaultValue('pagination')
            ->end()
            ->arrayNode('mapping')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('paths')
            ->prototype('scalar')->end()
            ->end()
            ->end()
            ->end()
        ;

        $this->addExceptionToStatusSection($rootNode);
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
            ->ifTrue(static fn ($v): bool => (bool) array_filter(array_keys($v), static fn ($item) => !preg_match('/^[a-zA-Z0-9._-]+$/', $item)))
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
            ->scalarNode('asset_package')->defaultNull()->info('Specify an asset package name to use.')->end()
            ->booleanNode('enable_swagger')->defaultTrue()->info('Enable the Swagger documentation and export.')->end()
            ->booleanNode('enable_swagger_ui')->defaultValue(class_exists(TwigBundle::class))->info('Enable Swagger UI')->end()
            ->booleanNode('enable_re_doc')->defaultValue(class_exists(TwigBundle::class))->info('Enable ReDoc')->end()
            ->booleanNode('enable_docs')->defaultTrue()->info('Enable the docs')->end()
            ->variableNode('swagger_ui_extra_configuration')
            ->defaultValue([])
            ->validate()
            ->ifTrue(static fn ($v): bool => is_array($v) === false)
            ->thenInvalid('The swagger_ui_extra_configuration parameter must be an array.')
            ->end()
            ->info('To pass extra configuration to Swagger UI, like docExpansion or filter.')
            ->end()
            ->end()
        ;
    }

    private function addExceptionToStatusSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
            ->arrayNode('exception_to_status')
            ->defaultValue([
                SerializerExceptionInterface::class => Response::HTTP_BAD_REQUEST,
                ValidationFailedException::class => Response::HTTP_BAD_REQUEST,
            ])
            ->info('The list of exceptions mapped to their HTTP status code.')
            ->normalizeKeys(false)
            ->useAttributeAsKey('exception_class')
            ->prototype('integer')->end()
            ->validate()
            ->ifArray()
            ->then(static function (array $exceptionToStatus): array {
                foreach ($exceptionToStatus as $httpStatusCode) {
                    if ($httpStatusCode < 100 || $httpStatusCode >= 600) {
                        throw new InvalidConfigurationException(sprintf('The HTTP status code "%s" is not valid.', $httpStatusCode));
                    }
                }

                return $exceptionToStatus;
            })
            ->end()
            ->end()
            ->end()
        ;
    }
}
