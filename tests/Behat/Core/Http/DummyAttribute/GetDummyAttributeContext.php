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
use ApiScout\Tests\Fixtures\Assert\Assertion;
use Behat\Gherkin\Node\TableNode;

/**
 * Get DummyAttribute controller test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDummyAttributeContext extends BaseContext
{
    private const GET_DUMMY_PATH = 'dummies_attribute';

    /**
     * @When one get a dummy attribute with:
     */
    public function when(TableNode $inputs): void
    {
        $inputs = $inputs->getRowsHash();

        Assertion::keyExists($inputs, 'id');

        $this->request(
            HttpRequest::METHOD_GET,
            self::GET_DUMMY_PATH.'/'.(int) $inputs['id'],
        );
    }
}
