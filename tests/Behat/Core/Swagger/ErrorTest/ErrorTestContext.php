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

namespace ApiScout\Tests\Behat\Core\Swagger\ErrorTest;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * ErrorTest Swagger response test
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ErrorTestContext extends BaseContext
{
    /**
     * @Then error controllers should not be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertArrayNotHasKey('/empty/payload', $response['paths']);
        Assert::assertArrayNotHasKey('Error.NotEmptyInput', $response['components']['schemas']);
        Assert::assertArrayNotHasKey('Error.NotEmptyOutput', $response['components']['schemas']);

        Assert::assertArrayNotHasKey('/custom/error', $response['paths']);
        Assert::assertArrayNotHasKey('Error.Response', $response['components']['schemas']);
    }
}
