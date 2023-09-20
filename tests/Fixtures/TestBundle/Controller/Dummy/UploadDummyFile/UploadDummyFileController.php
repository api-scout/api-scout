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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\UploadDummyFile;

use ApiScout\Attribute\Post;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final class UploadDummyFileController extends AbstractController
{
    #[Post('/dummies/upload_file', name: 'app_upload_dummy_file', resource: Dummy::class)]
    public function __invoke(
        #[MapRequestPayload] UploadDummyFilePayloadInput $dummyPayloadInput,
        Request $request
    ): DummyOutput {
        dd($request->files->all());
    }
}
