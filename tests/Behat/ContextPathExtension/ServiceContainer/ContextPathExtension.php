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

namespace ApiScout\Tests\Behat\ContextPathExtension\ServiceContainer;

use ApiScout\Tests\Behat\ContextPathExtension\Core\Application\Testwork\Suite\Generator\GenericSuiteGenerator;
use ApiScout\Tests\Behat\ContextPathExtension\Core\Domain\ContextsClassFinder;
use ApiScout\Tests\Behat\ContextPathExtension\Core\Infrastructure\Repositories\LocalFileRepository;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Behat\Testwork\Suite\ServiceContainer\SuiteExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ContextPathExtension implements Extension
{
    public function getConfigKey(): string
    {
        return 'context_path_extension';
    }

    public function initialize(ExtensionManager $extensionManager): void
    {
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
    }

    public function load(ContainerBuilder $container, array $config): void
    {
        $this->loadLocalFileRepository($container);
        $this->loadFileFinder($container);
        $this->loadGenericSuiteGeneratorDecorator($container);
    }

    public function process(ContainerBuilder $container): void
    {
    }

    private function loadLocalFileRepository(ContainerBuilder $container): void
    {
        $definition = new Definition(LocalFileRepository::class);

        $container->setDefinition(LocalFileRepository::class, $definition);
    }

    private function loadFileFinder(ContainerBuilder $container): void
    {
        $definition = new Definition(ContextsClassFinder::class, [
            new Reference(LocalFileRepository::class),
        ]);
        $container->setDefinition(ContextsClassFinder::class, $definition);
    }

    private function loadGenericSuiteGeneratorDecorator(ContainerBuilder $container): void
    {
        $definition = new Definition(GenericSuiteGenerator::class, [
            new Reference(SuiteExtension::GENERATOR_TAG.'.generic.inner'),
            new Reference(ContextsClassFinder::class),
        ]);
        $definition->addTag(SuiteExtension::GENERATOR_TAG, ['priority' => 50]);
        $definition->setDecoratedService(SuiteExtension::GENERATOR_TAG.'.generic', SuiteExtension::GENERATOR_TAG.'.generic.inner');
        $container->setDefinition(GenericSuiteGenerator::class, $definition);
    }
}
