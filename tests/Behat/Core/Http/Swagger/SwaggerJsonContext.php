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

namespace ApiScout\Tests\Behat\Core\Http\Swagger;

use ApiScout\HttpOperation;
use ApiScout\Tests\Behat\Core\Http\BaseContext;
use PHPUnit\Framework\Assert;

/**
 * Swagger Json service action test.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class SwaggerJsonContext extends BaseContext
{
    private const SWAGGER_JSON_PATH = '/api/docs.json';

    /**
     * @When one get the swagger json documentation
     */
    public function when(): void
    {
        $this->request(
            HttpOperation::METHOD_GET,
            self::SWAGGER_JSON_PATH
        );
    }

    /**
     * @Then swagger json documentation should be correctly configured
     */
    public function then(): void
    {
        $response = $this->getResponse()->toArray();

        Assert::assertArrayHasKey('openapi', $response);
        Assert::assertArrayHasKey('info', $response);
        Assert::assertArrayHasKey('servers', $response);
        Assert::assertArrayHasKey('paths', $response);
        Assert::assertArrayHasKey('components', $response);
        Assert::assertArrayHasKey('security', $response);
        Assert::assertArrayHasKey('tags', $response);

        Assert::assertSame($response['openapi'], '3.1.0');

        Assert::assertIsArray($response['info']);
        Assert::assertArrayHasKey('title', $response['info']);
        Assert::assertArrayHasKey('description', $response['info']);
        Assert::assertArrayHasKey('version', $response['info']);
        Assert::assertSame($response['info']['title'], '');
        Assert::assertSame($response['info']['description'], '');
        Assert::assertSame($response['info']['version'], '0.0.0');

        Assert::assertIsArray($response['servers']);
        Assert::assertArrayHasKey('url', $response['servers'][0]);
        Assert::assertArrayHasKey('description', $response['servers'][0]);
        Assert::assertSame($response['servers'][0]['url'], '/');
        Assert::assertSame($response['servers'][0]['description'], '');

        Assert::assertCount(9, $response['paths']);
        Assert::assertCount(7, $response['components']);
    }
}
