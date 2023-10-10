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

namespace ApiScout\Tests\Behat\Core\Swagger\Dummy;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * Patch Dummy Swagger response test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PatchDummyContext extends BaseContext
{
    /**
     * @Then patch dummy should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/dummies']['patch']);
        $patchDummyOperation = $response['paths']['/dummies']['patch'];

        Assert::assertArrayHasKey('responses', $patchDummyOperation);
        Assert::assertCount(3, $patchDummyOperation['responses']);
        Assert::assertArrayHasKey('200', $patchDummyOperation['responses']);
        Assert::assertArrayHasKey('400', $patchDummyOperation['responses']);
        Assert::assertArrayHasKey('404', $patchDummyOperation['responses']);

        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyOutput']);
        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyPayloadInput']);
    }
}
