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

namespace ApiScout\Tests\Behat\Core\Swagger\Dummy;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * Get Dummy Swagger test
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDummyContext extends BaseContext
{
    /**
     * @Then get dummy should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies/{id}']['get']);
        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyOutput']);

        Assert::assertNotEmpty($response['paths']['/dummies/{id}']['get']['parameters']);
        $parameter = array_shift($response['paths']['/dummies/{id}']['get']['parameters']);
        $parameterType = array_shift($parameter['schema']);

        Assert::assertSame('id', $parameter['name']);
        Assert::assertSame('path', $parameter['in']);
        Assert::assertTrue($parameter['required']);
        Assert::assertSame('integer', $parameterType);
    }
}
