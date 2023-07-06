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

namespace ApiScout\Tests\Behat\Core\Swagger;

use ApiScout\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;

class SwaggerContext extends BaseContext
{
    private const GET_DUMMY_PATH = '/api/docs.json';

    /**
     * @When one get the swagger json
     */
    public function when(): void
    {
        $this->request(
            HttpOperation::METHOD_GET,
            self::GET_DUMMY_PATH,
        );
    }
}
