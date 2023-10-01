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
use ApiScout\Tests\Behat\Symfony\HttpClient\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Exception\RuntimeException;

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

    #[Post(
        '/custom/error',
        name: 'app_test_custom_error',
        resource: 'Error',
        openapi: false
    )]
    public function customErrorTest(): Response
    {
        throw new RuntimeException('Serialization issue has happened');
    }
}
