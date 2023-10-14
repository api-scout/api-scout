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

namespace ApiScout\Tests\Behat\Core\Swagger;

use ApiScout\OpenApi\Http\Abstract\HttpRequest;
use ApiScout\Tests\Behat\Core\Http\BaseContext;

/**
 * Swagger json action request.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
class SwaggerContext extends BaseContext
{
    private const GET_SWAGGER_JSON = '/api/docs.json';

    /**
     * @When one get the swagger json
     */
    public function when(): void
    {
        $this->request(
            HttpRequest::METHOD_GET,
            self::GET_SWAGGER_JSON,
        );
    }
}
