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

use ApiScout\Tests\Behat\Symfony\HttpClient\Client;
use ApiScout\Tests\Fixtures\Symfony\EventListener\LoaderExceptionListener;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->bind('$httpTestClient', service(Client::class))
        ->bind(
            '$workingDir',
            sys_get_temp_dir().\DIRECTORY_SEPARATOR.'behat'.\DIRECTORY_SEPARATOR.md5(microtime().random_int(0, 10000)),
        )
    ;

    $services
        ->load('ApiScout\\Tests\\Behat\\', __DIR__.'/../../../../tests/Behat/*')
        ->load('ApiScout\\Tests\\Fixtures\\', __DIR__.'/../../../../tests/Fixtures/*')
        ->exclude(__DIR__.'/../../../../tests/Fixtures/app')
    ;

    $services->set('app.api_loader.kernel_browser', KernelBrowser::class);

    $services->set(Client::class)
        ->arg('$kernelBrowser', service('app.api_loader.kernel_browser'))
    ;

    $services
        ->set('app.api_loader.controller')
        ->synthetic()
        ->load(
            'ApiScout\\Tests\\Fixtures\\TestBundle\\Controller\\',
            __DIR__.'/../../TestBundle/Controller/**/*Controller.php',
        )
        ->tag('controller.service_arguments')
    ;

    $services
        ->set(LoaderExceptionListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException', 'priority' => -100])
    ;
};
