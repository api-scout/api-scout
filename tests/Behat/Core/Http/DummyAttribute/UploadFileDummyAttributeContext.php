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
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UploadFile DummyAttribute controller test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class UploadFileDummyAttributeContext extends BaseContext
{
    private const UPLOAD_FILE_DUMMY_ATTRIBUTE_PATH = 'upload_file_dummies_attribute';

    /**
     * @When one upload a dummy attribute file :fileName
     */
    public function when(string $fileName): void
    {
        $this->request(
            HttpRequest::METHOD_POST,
            self::UPLOAD_FILE_DUMMY_ATTRIBUTE_PATH,
            [
                'headers' => ['CONTENT_TYPE' => 'multipart/form-data'],
                'extra' => [
                    'files' => [
                        'file' => new UploadedFile($this->getFilePath($fileName), $fileName),
                    ],
                ],
            ],
        );
    }
}
