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

namespace ApiScout\Bridge\Symfony\Bundle\SwaggerUi;

use ApiScout\OpenApi\Factory\OpenApiFactoryInterface;
use ApiScout\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Request;

final class SwaggerJsonAction
{
    public function __construct(
        private readonly OpenApiFactoryInterface $openApiFactory,
    ) {
    }

    public function __invoke(Request $request): OpenApi
    {
        return $this->openApiFactory->__invoke(
            ['base_url' => $request->getBaseUrl() ?: '/'],
        );
    }
}
