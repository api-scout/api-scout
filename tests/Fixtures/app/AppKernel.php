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

namespace ApiScout\Tests\Fixtures\app;

use ApiScout\Bridge\Symfony\Bundle\ApiScoutBundle;
use ApiScout\Tests\Fixtures\TestBundle\TestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionFactory;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);

        // patch for behat/symfony2-extension not supporting %env(APP_ENV)%
        $this->environment = $_SERVER['APP_ENV'] ?? $environment;
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function registerBundles(): iterable
    {
        $bundles = [
            new ApiScoutBundle(),
            new DoctrineBundle(),
            new FriendsOfBehatSymfonyExtensionBundle(),
            new TwigBundle(),
            new FrameworkBundle(),
        ];

        $bundles[] = new TestBundle();

        return $bundles;
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__.'/config/routes/routes.php');
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        /**
         * @var string $env
         */
        $env = $container->getParameter('kernel.environment');

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__.'/config'),
            $env
        );

        $loader->load('services.php');

        $container->prependExtensionConfig('doctrine', [
            'dbal' => [
                'url' => 'sqlite:///'.__DIR__.'/var/app.db',
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'enable_lazy_ghost_objects' => true,
                'report_fields_where_declared' => true,
                'validate_xml_mapping' => true,
                'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
                'auto_mapping' => true,
                'mappings' => [
                    'App' => [
                        'is_bundle' => false,
                        'dir' => __DIR__.'/../TestBundle/Entity',
                        'prefix' => 'App\\Tests\\Entity',
                        'alias' => 'App\\Tests',
                    ],
                ],
            ],
        ]);

        $container->prependExtensionConfig('framework', [
            'secret' => 'marvin.fr',
            'validation' => ['enable_annotations' => true],
            'serializer' => ['enable_annotations' => true],
            'test' => null,
            'session' => class_exists(SessionFactory::class) ? ['storage_factory_id' => 'session.storage.factory.mock_file'] : ['storage_id' => 'session.storage.mock_file'],
            'profiler' => [
                'enabled' => true,
                'collect' => false,
            ],
            'router' => ['utf8' => true],
            'http_method_override' => false,
        ]);

        $twigConfig = ['strict_variables' => '%kernel.debug%'];
        if (interface_exists(ErrorRendererInterface::class)) {
            $twigConfig['exception_controller'] = null;
        }
        $container->prependExtensionConfig('twig', $twigConfig);

        $container->prependExtensionConfig('api_scout', [
            'title' => 'ApiScout',
            'description' => 'A library with a few tools, to auto document your api',
            'version' => '1.0.0',
            'openapi' => [
                'contact' => [
                    'name' => 'Marvin',
                    'url' => 'https://github.com/api-scout/api-scout',
                    'email' => 'marvincourcier.dev@gmail.com',
                ],
                'terms_of_service' => 'This will do',
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://fr.wikipedia.org/wiki/Licence_MIT',
                ],
            ],
            'exception_to_status' => [
                ValidationFailedException::class => Response::HTTP_BAD_REQUEST,
            ],
        ]);
    }

    /**
     * Gets the path to the configuration directory.
     */
    private function getConfigDir(): string
    {
        return $this->getProjectDir().'/config';
    }

    /**
     * Gets the path to the bundles configuration file.
     */
    private function getBundlesPath(): string
    {
        return $this->getConfigDir().'/bundles.php';
    }
}
