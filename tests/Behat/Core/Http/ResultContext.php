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

namespace ApiScout\Tests\Behat\Core\Http;

use ApiScout\OpenApi\Http\Abstract\HttpResponse;
use Assert\Assertion;
use PHPUnit\Framework\Assert;

/**
 * Response assertion.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ResultContext extends BaseContext
{
    /**
     * @Then success
     * @Then updated
     */
    public function ok(): void
    {
        Assert::assertSame(HttpResponse::HTTP_OK, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then no content
     */
    public function noContent(): void
    {
        Assert::assertSame(HttpResponse::HTTP_NO_CONTENT, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then created
     */
    public function created(): void
    {
        Assert::assertSame(HttpResponse::HTTP_CREATED, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then redirect
     */
    public function redirect(): void
    {
        Assert::assertSame(HttpResponse::HTTP_FOUND, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then invalid
     */
    public function invalid(): void
    {
        Assert::assertSame(HttpResponse::HTTP_BAD_REQUEST, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then unsupported media type
     */
    public function unsupportedMediaType(): void
    {
        Assert::assertSame(HttpResponse::HTTP_UNSUPPORTED_MEDIA_TYPE, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then request entity too large
     */
    public function requestEntityTooLarge(): void
    {
        Assert::assertSame(HttpResponse::HTTP_REQUEST_ENTITY_TOO_LARGE, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then not found
     */
    public function notFound(): void
    {
        Assert::assertSame(HttpResponse::HTTP_NOT_FOUND, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then forbidden
     */
    public function forbidden(): void
    {
        Assert::assertSame(HttpResponse::HTTP_FORBIDDEN, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then accepted
     */
    public function accepted(): void
    {
        Assert::assertSame(HttpResponse::HTTP_ACCEPTED, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then unauthorized
     */
    public function unauthorized(): void
    {
        Assert::assertSame(HttpResponse::HTTP_UNAUTHORIZED, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then internal server error
     */
    public function internalError(): void
    {
        Assert::assertSame(HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then invalid reason should be :exceptionType
     */
    public function invalidReasonShouldBe(string $exceptionType): void
    {
        $response = $this->getResponse()->toArray(false);

        $expectedResponseKeys = ['violations'];
        foreach ($expectedResponseKeys as $expectedResponseKey) {
            Assertion::inArray(
                $expectedResponseKey,
                array_keys($response),
                sprintf(
                    'Array "%s" did not contain the value "%s".',
                    json_encode(array_keys($response)),
                    json_encode($expectedResponseKey),
                ),
            );
        }

        foreach ($response['violations'] as $violationResponses) {
            Assert::assertSame($exceptionType, $violationResponses['path'].': '.$violationResponses['message']);
        }
    }
}
