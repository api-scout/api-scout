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

namespace ApiScout\OpenApi\Factory;

use ApiScout\OpenApi\OpenApi;

/**
 * Inspired by ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface.
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
interface OpenApiFactoryInterface
{
    /**
     * Creates an OpenApi class.
     */
    public function __invoke(array $context = []): OpenApi;
}
