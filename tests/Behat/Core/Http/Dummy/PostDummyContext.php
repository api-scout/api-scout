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
 * Post Dummy controller test
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PostDummyContext extends BaseContext
{
    private const POST_DUMMY_PATH = 'dummies';

    /**
     * @When one post a dummy with:
     */
    public function when(PyStringNode $content): void
    {
        $this->request(
            HttpOperation::METHOD_POST,
            self::POST_DUMMY_PATH,
            [
                'json' => $this->json($content->getRaw()),
            ]
        );
    }

    /**
     * @Then post dummy response should have:
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
