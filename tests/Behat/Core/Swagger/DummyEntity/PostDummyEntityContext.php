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

        Assert::assertArrayHasKey('requestBody', $postDummyEntityOperation);
        $componentsSchemasKeyInput = $this->getSchemaRefKey(
            $postDummyEntityOperation['requestBody']['content']['application/json']['schema']['$ref']
        );
        Assert::assertArrayHasKey($componentsSchemasKeyInput, $response['components']['schemas']);

        Assert::assertArrayHasKey('responses', $postDummyEntityOperation);
        $componentsSchemasKeyOutput = $this->getSchemaRefKey(
            $postDummyEntityOperation['responses']['201']['content']['application/json']['schema']['$ref']
        );
        Assert::assertArrayHasKey($componentsSchemasKeyOutput, $response['components']['schemas']);
        Assert::assertCount(2, $postDummyEntityOperation['responses']);
        Assert::assertArrayHasKey('201', $postDummyEntityOperation['responses']);
        Assert::assertArrayHasKey('400', $postDummyEntityOperation['responses']);

        $dummyReadProperties = $response['components']['schemas'][$componentsSchemasKeyInput]['properties'];
        Assert::assertArrayNotHasKey('id', $dummyReadProperties);
        Assert::assertArrayHasKey('firstName', $dummyReadProperties);
        Assert::assertArrayHasKey('lastName', $dummyReadProperties);
        Assert::assertArrayHasKey('addressEntity', $dummyReadProperties);

        $addressEntityProperties = $dummyReadProperties['addressEntity']['properties'];
        Assert::assertArrayHasKey('id', $addressEntityProperties);
        Assert::assertArrayHasKey('name', $addressEntityProperties);
        Assert::assertArrayHasKey('description', $addressEntityProperties);

        $dummyWriteProperties = $response['components']['schemas'][$componentsSchemasKeyOutput]['properties'];
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
