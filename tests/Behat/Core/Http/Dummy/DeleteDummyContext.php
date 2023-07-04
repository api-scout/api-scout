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

namespace ApiScout\Tests\Behat\Core\Http\Dummy;

use ApiScout\Core\Domain\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use ApiScout\Tests\Fixtures\TestBundle\Assert\Assertion;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

final class DeleteDummyContext extends BaseContext
{
    private const DELETE_DUMMY_PATH = 'dummies';

    /**
     * @When one delete a dummy with:
     */
    public function when(TableNode $inputs): void
    {
        $inputs = $inputs->getRowsHash();

        Assertion::keyExists($inputs, 'name');

        $this->request(
            HttpOperation::METHOD_DELETE,
            self::DELETE_DUMMY_PATH.'/'.$inputs['name']
        );
    }

    /**
     * @Then delete dummy response should be empty
     */
    public function then(): void
    {
        Assert::assertEmpty($this->getResponse()->getContent());
    }
}
