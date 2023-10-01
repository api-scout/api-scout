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

namespace ApiScout\Tests\Behat\Core\Swagger\DummyAttribute;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * Get DummyAttribute Swagger response test
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDummyAttributeContext extends BaseContext
{
    /**
     * @Then get dummy attribute should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray(false);

        Assert::assertNotEmpty($response['paths']['/dummies_attribute/{id}']['get']);
        Assert::assertNotEmpty($response['components']['schemas']['DummyAttribute.DummyAttributeOutput']);

        Assert::assertNotEmpty($response['paths']['/dummies_attribute/{id}']['get']['parameters']);
        $parameter = array_shift($response['paths']['/dummies_attribute/{id}']['get']['parameters']);
        $parameterType = array_shift($parameter['schema']);

        Assert::assertSame('id', $parameter['name']);
        Assert::assertSame('path', $parameter['in']);
        Assert::assertTrue($parameter['required']);
        Assert::assertSame('string', $parameterType);
    }
}
