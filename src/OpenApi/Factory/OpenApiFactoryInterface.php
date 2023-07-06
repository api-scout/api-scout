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

namespace ApiScout\OpenApi\Factory;

use ApiScout\OpenApi\OpenApi;

interface OpenApiFactoryInterface
{
    /**
     * Creates an OpenApi class.
     */
    public function __invoke(array $context = []): OpenApi;
}
