<?php

declare(strict_types=1);

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiScout\Tests\Behat\Core\Http\DummyAttribute;

use ApiScout\Core\Domain\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use ApiScout\Tests\Fixtures\TestBundle\Assert\Assertion;
use Behat\Gherkin\Node\TableNode;

final class DeleteDummyAttributeContext extends BaseContext
{
    private const DELETE_DUMMY_ATTRIBUTE_PATH = 'dummies_attribute';

    /**
     * @When one delete a dummy attribute with:
     */
    public function when(TableNode $inputs): void
    {
        $inputs = $inputs->getRowsHash();

        Assertion::keyExists($inputs, 'id');

        $this->request(
            HttpOperation::METHOD_DELETE,
            self::DELETE_DUMMY_ATTRIBUTE_PATH.'/'.(int) $inputs['id']
        );
    }
}
