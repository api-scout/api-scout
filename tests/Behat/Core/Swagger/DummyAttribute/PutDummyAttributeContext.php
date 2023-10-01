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
 * Put DummyAttribute Swagger response test
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PutDummyAttributeContext extends BaseContext
{
    /**
     * @Then put dummy attribute should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies_attribute']['put']);
        Assert::assertNotEmpty($response['components']['schemas']['DummyAttribute.DummyAttributeOutput']);
        Assert::assertNotEmpty($response['components']['schemas']['DummyAttribute.DummyAttributePayloadInput']);
    }
}
