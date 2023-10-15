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

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;

return static function (ContainerConfigurator $container): void {
    $array = ['strict_variables' => '%kernel.debug%'];

    if (interface_exists(ErrorRendererInterface::class)) {
        $array['exception_controller'] = null;
    }

    $container->extension('twig', $array);
};
