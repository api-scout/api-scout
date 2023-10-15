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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidationFailedException;

return static function (ContainerConfigurator $container): void {
    $array = [
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
    ];

    $container->extension('api_scout', $array);
};
