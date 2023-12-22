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
final class GetArrayDummyContext extends BaseContext
{
    /**
     * @Then get dummy array should be configured
     */
    public function then(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies_array']['get']);
        $getDummiesArrayResponse = $response['paths']['/dummies_array']['get'];

        Assert::assertArrayHasKey('summary', $getDummiesArrayResponse);
        Assert::assertArrayHasKey('description', $getDummiesArrayResponse);
        Assert::assertSame(
            'Retrieve a Dummy array',
            $getDummiesArrayResponse['summary']
        );
        Assert::assertSame(
            'Retrieve a Dummy array',
            $getDummiesArrayResponse['description']
        );
    }
}
