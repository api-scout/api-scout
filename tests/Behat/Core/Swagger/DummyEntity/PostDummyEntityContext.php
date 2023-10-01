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

namespace ApiScout\Tests\Behat\Core\Swagger\DummyEntity;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * Post DummyEntity Swagger response test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PostDummyEntityContext extends BaseContext
{
    /**
     * @Then post dummy entity should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/api/dummies_entity']['post']);
        Assert::assertNotEmpty($response['components']['schemas']['DummyEntity.DummyEntity']);
        Assert::assertNotEmpty($response['components']['schemas']['DummyEntity.DummyEntity']);
    }
}
