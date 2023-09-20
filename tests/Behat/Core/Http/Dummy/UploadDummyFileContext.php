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

namespace ApiScout\Tests\Behat\Core\Http\Dummy;

use ApiScout\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadDummyFileContext extends BaseContext
{
    private const POST_DUMMY_PATH = 'dummies/upload_file';

    /**
     * @When one upload a dummy file :fileName
     */
    public function when(string $fileName): void
    {
        $this->request(
            HttpOperation::METHOD_POST,
            self::POST_DUMMY_PATH,
            [
                'headers' => ['CONTENT_TYPE' => 'multipart/form-data'],
                'extra' => [
                    'files' => [
                        'icon' => new UploadedFile($this->getFilePath($fileName), $fileName),
                    ],
                ],
            ]
        );

        dd($this->getResponse()->toArray(false));
    }

    /**
     * @Then upload dummy file response should have:
     */
    public function then(PyStringNode $content): void
    {
        $content = $this->json($content->getRaw());

        Assert::assertSame(
            $content,
            $this->getResponse()->toArray()
        );
    }
}
