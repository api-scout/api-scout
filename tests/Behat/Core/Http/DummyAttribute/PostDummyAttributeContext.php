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

namespace ApiScout\Tests\Behat\Core\Http\DummyAttribute;

use ApiScout\OpenApi\Http\Abstract\HttpRequest;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Post DummyAttribute controller test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PostDummyAttributeContext extends BaseContext
{
    private const POST_DUMMY_ATTRIBUTE_PATH = 'dummies_attribute';

    /**
     * @When one post a dummy attribute with:
     */
    public function when(PyStringNode $content): void
    {
        $this->request(
            HttpRequest::METHOD_POST,
            self::POST_DUMMY_ATTRIBUTE_PATH,
            [
                'json' => $this->json($content->getRaw()),
            ]
        );
    }
}
