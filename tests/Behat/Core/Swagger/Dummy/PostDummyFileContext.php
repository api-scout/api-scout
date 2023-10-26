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
use Symfony\Component\HttpFoundation\Response;

/**
 * Post Dummy File Swagger response test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PostDummyFileContext extends BaseContext
{
    /**
     * @Then post dummy file should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        $dummyFileEndpoint = $response['paths']['/dummies/file']['post'];

        Assert::assertNotEmpty($dummyFileEndpoint);
        Assert::assertArrayHasKey('responses', $dummyFileEndpoint);

        Assert::assertArrayHasKey(Response::HTTP_CREATED, $dummyFileEndpoint['responses']);
        Assert::assertArrayHasKey(Response::HTTP_BAD_REQUEST, $dummyFileEndpoint['responses']);
        Assert::assertArrayHasKey(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $dummyFileEndpoint['responses']);
        Assert::assertSame(
            'Validation Failed',
            $dummyFileEndpoint['responses'][Response::HTTP_UNSUPPORTED_MEDIA_TYPE]['description']
        );

        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyFileOutput']);
        Assert::assertNotEmpty($response['components']['schemas']['Dummy.DummyPayloadFileInput']);
    }
}
