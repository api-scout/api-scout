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

use ApiScout\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use ApiScout\Tests\Fixtures\TestBundle\Assert\Assertion;
use Behat\Gherkin\Node\TableNode;

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
            HttpOperation::METHOD_GET,
            self::GET_DUMMY_PATH.'/'.(int) $inputs['id'],
        );
    }
}
