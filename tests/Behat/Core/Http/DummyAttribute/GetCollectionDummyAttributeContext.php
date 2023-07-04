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

final class GetCollectionDummyAttributeContext extends BaseContext
{
    private const GET_COLLECTION_DUMMY_PATH = 'dummies_attribute';

    /**
     * @When one get a dummy attribute collection
     */
    public function when(): void
    {
        $this->request(
            HttpOperation::METHOD_GET,
            self::GET_COLLECTION_DUMMY_PATH,
        );
    }
}
