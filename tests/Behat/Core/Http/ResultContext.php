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

use ApiScout\OpenApi\Http\AbstractResponse;
use Assert\Assertion;
use PHPUnit\Framework\Assert;

final class ResultContext extends BaseContext
{
    /**
     * @Then success
     * @Then updated
     */
    public function ok(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_OK, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then no content
     */
    public function noContent(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_NO_CONTENT, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then created
     */
    public function created(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_CREATED, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then redirect
     */
    public function redirect(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_FOUND, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then invalid
     */
    public function invalid(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_BAD_REQUEST, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then not found
     */
    public function notFound(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_NOT_FOUND, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then forbidden
     */
    public function forbidden(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_FORBIDDEN, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then accepted
     */
    public function accepted(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_ACCEPTED, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then unauthorized
     */
    public function unauthorized(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_UNAUTHORIZED, $this->getResponse()->getStatusCode());
    }

    /**
     * @Then internal server error
     */
    public function internalError(): void
    {
        Assert::assertSame(AbstractResponse::HTTP_INTERNAL_SERVER_ERROR, $this->getResponse()->getStatusCode());
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
                    json_encode($expectedResponseKey)
                )
            );
        }

        foreach ($response['violations'] as $violationResponses) {
            Assert::assertSame($exceptionType, $violationResponses['path'].': '.$violationResponses['message']);
        }
    }
}
