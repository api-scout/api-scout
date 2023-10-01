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

namespace ApiScout\Tests\Behat\Core\Swagger\DummyAttribute;

use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * UploadFile DummyAttribute Swagger response test
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class UploadFileDummyAttributeContext extends BaseContext
{
    /**
     * @Then upload dummy attribute file should be configured
     */
    public function when(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertNotEmpty($response['paths']['/upload_file_dummies_attribute']['post']);

        $uploadDummiesAttributeFile = $response['paths']['/upload_file_dummies_attribute']['post'];

        Assert::assertIsArray($uploadDummiesAttributeFile['requestBody']);
        Assert::assertArrayHasKey('content', $uploadDummiesAttributeFile['requestBody']);

        Assert::assertIsArray($uploadDummiesAttributeFile['requestBody']['content']);
        Assert::assertArrayHasKey('multipart/form-data', $uploadDummiesAttributeFile['requestBody']['content']);

        $multipartForm = $uploadDummiesAttributeFile['requestBody']['content']['multipart/form-data'];

        Assert::assertIsArray($multipartForm['schema']['properties']['file']);
        Assert::assertArrayHasKey('type', $multipartForm['schema']['properties']['file']);
        Assert::assertArrayHasKey('format', $multipartForm['schema']['properties']['file']);
        Assert::assertSame($multipartForm['schema']['properties']['file']['type'], 'string');
        Assert::assertSame($multipartForm['schema']['properties']['file']['format'], 'binary');
    }
}
