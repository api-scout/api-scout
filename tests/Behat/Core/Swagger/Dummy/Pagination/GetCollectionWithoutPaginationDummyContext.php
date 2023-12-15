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
final class GetCollectionWithoutPaginationDummyContext extends BaseContext
{
    /**
     * @Then get collection without pagination dummy should be configured
     */
    public function then(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies_without_pagination']['get']);
        $getDummiesWithoutPaginationResponse = $response['paths']['/dummies_without_pagination']['get'];

        Assert::assertArrayHasKey('summary', $getDummiesWithoutPaginationResponse);
        Assert::assertArrayHasKey('description', $getDummiesWithoutPaginationResponse);
        Assert::assertSame(
            'Retrieve the Collection of a Dummy resource without pagination',
            $getDummiesWithoutPaginationResponse['summary'],
        );
        Assert::assertSame(
            'Retrieve the Collection of a Dummy resource without pagination',
            $getDummiesWithoutPaginationResponse['description'],
        );

        $parameters = $response['paths']['/dummies_without_pagination']['get']['parameters'];
        Assert::assertCount(2, $parameters);

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
}
