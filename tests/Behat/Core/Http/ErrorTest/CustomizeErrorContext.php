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

namespace ApiScout\Tests\Behat\Core\Http\ErrorTest;

use ApiScout\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;

/**
 * Post CustomizeError controller test
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class CustomizeErrorContext extends BaseContext
{
    private const POST_CUSTOM_ERROR_PATH = 'custom/error';

    /**
     * @When one post and trigger a custom error
     */
    public function when(): void
    {
        $this->request(
            HttpOperation::METHOD_POST,
            self::POST_CUSTOM_ERROR_PATH,
        );
    }
}
