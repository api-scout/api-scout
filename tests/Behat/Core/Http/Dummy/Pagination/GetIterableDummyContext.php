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
final class GetIterableDummyContext extends BaseContext
{
    private const GET_ITERABLE_DUMMY_PATH = 'dummies_iterable';

    /**
     * @When one get a dummy iterable
     */
    public function when(): void
    {
        $this->request(
            HttpRequest::METHOD_GET,
            self::GET_ITERABLE_DUMMY_PATH,
        );
    }

    /**
     * @Then get dummy iterable response should be:
     */
    public function then(PyStringNode $content): void
    {
        $content = $this->json($content->getRaw());

        Assert::assertSame(
            $content,
            $this->getResponse()->toArray()
        );
    }
}
