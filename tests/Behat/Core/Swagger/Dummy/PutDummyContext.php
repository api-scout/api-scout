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
 * Put Dummy Swagger response test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PutDummyContext extends BaseContext
{
    /**
     * @Then put dummy should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies']['post']);
        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyOutput']);
        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyPayloadInput']);
    }
}
