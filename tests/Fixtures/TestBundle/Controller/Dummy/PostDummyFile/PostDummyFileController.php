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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\PostDummyFile;

use ApiScout\Attribute\Post;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PostDummyFileController
{
    #[Post(
        '/dummies/file',
        name: 'app_add_dummy_file',
        resource: Dummy::class,
        exceptionToStatus: [ValidationFailedException::class => 415],
    )]
    public function __invoke(
        #[MapRequestPayload] DummyPayloadFileInput $dummyPayloadInput,
    ): DummyFileOutput {
        return new DummyFileOutput('dummyFileName.fake');
    }
}
