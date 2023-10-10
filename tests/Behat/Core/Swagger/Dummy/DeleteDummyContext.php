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
 * Delete DeleteDummy Swagger response test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class DeleteDummyContext extends BaseContext
{
    /**
     * @Then delete dummy should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies/{name}']['delete']);
        $deleteDummyOperation = $response['paths']['/dummies/{name}']['delete'];

        Assert::assertArrayHasKey('responses', $deleteDummyOperation);
        Assert::assertCount(3, $deleteDummyOperation['responses']);
        Assert::assertArrayHasKey('204', $deleteDummyOperation['responses']);
        Assert::assertArrayHasKey('400', $deleteDummyOperation['responses']);
        Assert::assertArrayHasKey('404', $deleteDummyOperation['responses']);

        //        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyOutput']);

        Assert::assertNotEmpty($response['paths']['/dummies/{name}']['delete']['parameters']);
        $parameter = array_shift($response['paths']['/dummies/{name}']['delete']['parameters']);
        $parameterType = array_shift($parameter['schema']);

        Assert::assertSame('name', $parameter['name']);
        Assert::assertSame('path', $parameter['in']);
        Assert::assertTrue($parameter['required']);
        Assert::assertSame('string', $parameterType);
    }
}
