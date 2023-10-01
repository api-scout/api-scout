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

namespace ApiScout\Tests\Behat\Core\Http\Dummy;

use ApiScout\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

/**
 * GetCollection Dummy controller test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetCollectionDummyContext extends BaseContext
{
    private const GET_COLLECTION_DUMMY_PATH = 'dummies';

    /**
     * @When one get a dummy collection
     */
    public function when(): void
    {
        $this->request(
            HttpOperation::METHOD_GET,
            self::GET_COLLECTION_DUMMY_PATH,
        );
    }

    /**
     * @Then get dummy collection response should be:
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
