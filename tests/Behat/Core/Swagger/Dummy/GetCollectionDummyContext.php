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

namespace ApiScout\Tests\Behat\Core\Swagger\Dummy;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

final class GetCollectionDummyContext extends BaseContext
{
    /**
     * @Then get collection dummy filters should be configured
     */
    public function thenDummyFiltersShouldBeConfigured(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies']['get']);

        $parameters = $response['paths']['/dummies']['get']['parameters'];
        Assert::assertCount(2, $parameters);

        Assert::assertSame('name', $parameters[0]['name']);
        Assert::assertSame('query', $parameters[0]['in']);
        Assert::assertSame('The name of the champion', $parameters[0]['description']);
        Assert::assertSame(false, $parameters[0]['required']);
        Assert::assertSame(false, $parameters[0]['deprecated']);
        Assert::assertSame('string', $parameters[0]['schema']['type']);

        Assert::assertSame('page', $parameters[1]['name']);
        Assert::assertSame('query', $parameters[1]['in']);
        Assert::assertSame('The page my mate', $parameters[1]['description']);
        Assert::assertSame(true, $parameters[1]['required']);
        Assert::assertSame(false, $parameters[1]['deprecated']);
        Assert::assertSame('string', $parameters[1]['schema']['type']);
    }

    /**
     * @Then get collection dummy should be configured
     */
    public function then(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies']['get']);
        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyCollectionOutput']);
    }
}
