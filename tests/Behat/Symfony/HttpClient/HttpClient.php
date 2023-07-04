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

namespace ApiScout\Tests\Behat\Symfony\HttpClient;

use LogicException;

final class HttpClient
{
    private static ?Client $httpClient = null;

    public static function httpClient(?Client $httpClient = null): Client
    {
        if ($httpClient !== null) {
            self::$httpClient = $httpClient;
        }

        if (!self::$httpClient instanceof Client) {
            throw new LogicException('No HTTP Client');
        }

        return self::$httpClient;
    }
}
