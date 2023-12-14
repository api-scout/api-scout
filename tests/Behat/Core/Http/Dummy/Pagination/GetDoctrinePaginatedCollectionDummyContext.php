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

namespace ApiScout\Tests\Behat\Core\Http\Dummy\Pagination;

use ApiScout\OpenApi\Http\Abstract\HttpRequest;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

/**
 * GetCollection Dummy controller test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDoctrinePaginatedCollectionDummyContext extends BaseContext
{
    private const GET_DOCTRINE_PAGINATED_COLLECTION_DUMMY_PATH = 'doctrine_paginated_dummies';

    /**
     * @When one get a doctrine paginated dummy collection with :name at page :page
     */
    public function when(string $name, int $page): void
    {
        $this->request(
            HttpRequest::METHOD_GET,
            self::GET_DOCTRINE_PAGINATED_COLLECTION_DUMMY_PATH,
            [
                'query' => [
                    'name' => $name,
                    'page' => $page,
                ],
            ],
        );
    }

    /**
     * @Then get paginated dummy collection response should be:
     */
    public function then(PyStringNode $content): void
    {
        $content = $this->json($content->getRaw());

        Assert::assertSame(
            $content,
            $this->getResponse()->toArray(),
        );
    }
}
