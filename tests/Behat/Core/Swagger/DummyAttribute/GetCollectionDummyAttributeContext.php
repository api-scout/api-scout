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

namespace ApiScout\Tests\Behat\Core\Swagger\DummyAttribute;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

final class GetCollectionDummyAttributeContext extends BaseContext
{
    /**
     * @Then get collection dummy attribute should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies_attribute']['get']);
        Assert::assertNotEmpty($response['components']['schemas']['DummyAttribute.DummyAttributeOutput']);
        Assert::assertNotEmpty($response['components']['schemas']['DummyAttribute.DummyAttributeQueryInput']);
    }
}
