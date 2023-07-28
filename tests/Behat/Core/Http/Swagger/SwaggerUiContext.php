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

namespace ApiScout\Tests\Behat\Core\Http\Swagger;

use ApiScout\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;

final class SwaggerUiContext extends BaseContext
{
    private const SWAGGER_UI_PATH = '/api/docs';

    /**
     * @When one get the swagger documentation
     */
    public function when(): void
    {
        $this->request(
            HttpOperation::METHOD_GET,
            self::SWAGGER_UI_PATH
        );
    }
}
