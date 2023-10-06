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

        $postDummyEntityOperation = $response['paths']['/api/dummies_entity']['post'];

        dd($postDummyEntityOperation);


        Assert::assertArrayHasKey('responses', $postDummyEntityOperation);
        Assert::assertCount(2, $postDummyEntityOperation['responses']);
        Assert::assertArrayHasKey('201', $postDummyEntityOperation['responses']);
        Assert::assertArrayHasKey('400', $postDummyEntityOperation['responses']);


        Assert::assertNotEmpty($response['components']['schemas']['DummyEntity.DummyRead']);
        $dummyReadProperties = $response['components']['schemas']['DummyEntity.DummyRead']['properties'];
        Assert::assertArrayNotHasKey('id', $dummyReadProperties);
        Assert::assertArrayHasKey('firstName', $dummyReadProperties);
        Assert::assertArrayHasKey('lastName', $dummyReadProperties);
        Assert::assertArrayHasKey('addressEntity', $dummyReadProperties);

        $addressEntityProperties = $dummyReadProperties['addressEntity']['properties'];
        Assert::assertArrayHasKey('id', $addressEntityProperties);
        Assert::assertArrayHasKey('name', $addressEntityProperties);
        Assert::assertArrayHasKey('description', $addressEntityProperties);

        Assert::assertNotEmpty($response['components']['schemas']['DummyEntity.DummyWrite']);
        $dummyWriteProperties = $response['components']['schemas']['DummyEntity.DummyWrite']['properties'];
        Assert::assertArrayNotHasKey('id', $dummyWriteProperties);
        Assert::assertArrayHasKey('firstName', $dummyWriteProperties);
        Assert::assertArrayHasKey('lastName', $dummyWriteProperties);
        Assert::assertArrayHasKey('addressEntity', $dummyWriteProperties);
        $addressEntityProperties = $dummyWriteProperties['addressEntity']['properties'];
        Assert::assertArrayNotHasKey('id', $addressEntityProperties);
        Assert::assertArrayHasKey('name', $addressEntityProperties);
        Assert::assertArrayHasKey('description', $addressEntityProperties);
    }
}
