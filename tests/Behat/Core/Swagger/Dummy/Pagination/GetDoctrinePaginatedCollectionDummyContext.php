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

namespace ApiScout\Tests\Behat\Core\Swagger\Dummy\Pagination;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * GetCollection Dummy Swagger response test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDoctrinePaginatedCollectionDummyContext extends BaseContext
{
    /**
     * @Then get doctrine paginated collection dummy filters should be configured
     */
    public function thenDummyFiltersShouldBeConfigured(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/doctrine_paginated_dummies']['get']);
        $getDoctrinePaginatedDummyOperation = $response['paths']['/doctrine_paginated_dummies']['get'];

        Assert::assertArrayHasKey('responses', $getDoctrinePaginatedDummyOperation);
        Assert::assertCount(1, $getDoctrinePaginatedDummyOperation['responses']);
        Assert::assertArrayHasKey('200', $getDoctrinePaginatedDummyOperation['responses']);

        $parameters = $getDoctrinePaginatedDummyOperation['parameters'];
        Assert::assertCount(4, $parameters);

        Assert::assertSame('name', $parameters[0]['name']);
        Assert::assertSame('query', $parameters[0]['in']);
        Assert::assertSame('The name of the champion', $parameters[0]['description']);
        Assert::assertSame(false, $parameters[0]['required']);
        Assert::assertSame(false, $parameters[0]['deprecated']);
        Assert::assertSame('string', $parameters[0]['schema']['type']);

        Assert::assertSame('city', $parameters[1]['name']);
        Assert::assertSame('query', $parameters[1]['in']);
        Assert::assertSame('', $parameters[1]['description']);
        Assert::assertSame(false, $parameters[1]['required']);
        Assert::assertSame(false, $parameters[1]['deprecated']);
        Assert::assertSame('string', $parameters[1]['schema']['type']);
    }

    /**
     * @Then get doctrine paginated collection dummy should be configured
     */
    public function then(): void
    {
        $response = $this->getResponse()->toArray();

        $getDoctrinePaginatedDummyOperation = $response['paths']['/doctrine_paginated_dummies']['get'];

        Assert::assertNotEmpty($getDoctrinePaginatedDummyOperation);
        Assert::assertNotEmpty($response['components']['schemas']['Dummy.Pagination']);
    }
}
