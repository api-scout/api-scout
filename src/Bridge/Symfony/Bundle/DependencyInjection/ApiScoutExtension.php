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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class ApiScoutExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $configuration->getConfigTreeBuilder();
        $configs = $this->processConfiguration($configuration, $configs);

        $this->registerCommonConfiguration($configs, $container);

        /**
         * @var string $env
         */
        $env = $container->getParameter('kernel.environment');

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config'),
            $env
        );

        $loader->load('api.php');
        $loader->load('openapi.php');
        $loader->load('pagination.php');
        $loader->load('resource.php');
        $loader->load('swagger.php');
        $loader->load('symfony.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
    }

    private function registerCommonConfiguration(
        array $configs,
        ContainerBuilder $container
    ): void {
        $container->setParameter('api_scout.title', $configs['title']);
        $container->setParameter('api_scout.description', $configs['description']);
        $container->setParameter('api_scout.version', $configs['version']);
        $container->setParameter('api_scout.response_item_key', $configs['response_item_key']);
        $container->setParameter('api_scout.exception_to_status', $configs['exception_to_status']);

        $container->setParameter('api_scout.pagination.enabled', $configs['pagination']['enabled']);
        $container->setParameter('api_scout.pagination.page_parameter_name', $configs['pagination']['page_parameter_name']);
        $container->setParameter('api_scout.pagination.items_per_page', $configs['pagination']['items_per_page']);
        $container->setParameter('api_scout.pagination.maximum_items_per_page', $configs['pagination']['maximum_items_per_page']);

        $container->setParameter('api_scout.oauth.enabled', $configs['oauth']['enabled']);
        $container->setParameter('api_scout.oauth.type', $configs['oauth']['type']);
        $container->setParameter('api_scout.oauth.flow', $configs['oauth']['flow']);
        $container->setParameter('api_scout.oauth.token_url', $configs['oauth']['token_url']);
        $container->setParameter('api_scout.oauth.authorization_url', $configs['oauth']['authorization_url']);
        $container->setParameter('api_scout.oauth.refresh_url', $configs['oauth']['refresh_url']);
        $container->setParameter('api_scout.oauth.scopes', $configs['oauth']['scopes']);

        $container->setParameter('api_scout.openapi.api_keys', $configs['openapi']['api_keys']);
        $container->setParameter('api_scout.openapi.contact', $configs['openapi']['contact']['name']);
        $container->setParameter('api_scout.openapi.url', $configs['openapi']['contact']['url']);
        $container->setParameter('api_scout.openapi.email', $configs['openapi']['contact']['email']);
        $container->setParameter('api_scout.openapi.terms_of_service', $configs['openapi']['terms_of_service']);
        $container->setParameter('api_scout.openapi.license.name', $configs['openapi']['license']['name']);
        $container->setParameter('api_scout.openapi.license.url', $configs['openapi']['license']['url']);

        $container->setParameter(
            'api_scout.mapping.paths',
            $this->registerMappingPathsConfiguration($container, $configs)
        );

        $container->setParameter('api_scout.enable_swagger_ui', $configs['enable_swagger_ui']);
        $container->setParameter('api_scout.enable_re_doc', $configs['enable_re_doc']);
        $container->setParameter('api_scout.enable_docs', $configs['enable_docs']);
        $container->setParameter('api_scout.asset_package', $configs['asset_package']);
        $container->setParameter('api_scout.swagger_ui_extra_configuration', $configs['swagger_ui_extra_configuration']);
    }

    private function registerMappingPathsConfiguration(ContainerBuilder $container, array $config): array
    {
        /**
         * @var array<string> $paths
         */
        $paths = $config['mapping']['paths'];

        if ($paths === []) {
            /**
             * @var string $projectDir
             */
            $projectDir = $container->getParameter('kernel.project_dir');
            $dir = $projectDir.'/src/Controller';

            if (is_dir($dir)) {
                $paths[] = $dir;
            }
        }

        return $paths;
    }
}
