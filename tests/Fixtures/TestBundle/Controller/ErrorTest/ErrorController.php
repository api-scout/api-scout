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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\ErrorTest;

use ApiScout\Attribute\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ErrorController extends AbstractController
{
    #[Post(
        '/empty/payload',
        name: 'app_test_when_empty_payload_should_not_be_empty',
        resource: 'Error',
        openapi: false
    )]
    public function whenEmptyPayloadShouldNotBeEmptyTest(
        #[MapRequestPayload] NotEmptyInput $dummyPayloadInput,
    ): NotEmptyOutput {
        return new NotEmptyOutput('Marvin');
    }
}
