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

use ApiScout\Attribute\Delete;
use ApiScout\Attribute\Get;
use ApiScout\Attribute\GetCollection;
use ApiScout\Attribute\Patch;
use ApiScout\Attribute\Post;
use ApiScout\Attribute\Put;
use ApiScout\Operation;
use ArrayObject;
use ReflectionMethod;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * The Symfony extension.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ApiScoutExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $configuration->getConfigTreeBuilder();
        $configs = $this->processConfiguration($configuration, $configs);

        $this->registerCommonConfiguration($configs, $container);
        $this->registerOperationsConfiguration($container);

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
        $loader->load('resource.php');
        $loader->load('response.php');
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
        $container->setParameter('api_scout.response_pagination_key', $configs['response_pagination_key']);
        $container->setParameter('api_scout.exception_to_status', $configs['exception_to_status']);

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

        $container->setParameter('api_scout.enable_swagger_ui', $configs['enable_swagger_ui']);
        $container->setParameter('api_scout.enable_re_doc', $configs['enable_re_doc']);
        $container->setParameter('api_scout.enable_docs', $configs['enable_docs']);
    }

    private function registerOperationsConfiguration(ContainerBuilder $container): void
    {
        $operationAttributes = [
            GetCollection::class,
            Get::class,
            Post::class,
            Put::class,
            Patch::class,
            Delete::class,
        ];

        $operationMethodsMap = $container->register('.api_scout.operation_methods_map', ArrayObject::class);

        foreach ($operationAttributes as $operation) {
            $container->registerAttributeForAutoconfiguration(
                $operation,
                /** @phpstan-ignore-next-line all operation specified attribute are instance of Operation */
                static function (
                    ChildDefinition $definition,
                    Operation $attribute,
                    ReflectionMethod $reflector,
                ) use ($operationMethodsMap): void {
                    $operationMethodsMap->addMethodCall('append', [
                        sprintf('%s::%s', $reflector->getDeclaringClass()->name, $reflector->name),
                    ]);
                }
            );
        }
    }
}
