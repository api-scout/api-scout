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

use ApiScout\Tests\Behat\Symfony\HttpClient\Client;
use ApiScout\Tests\Behat\Symfony\HttpClient\HttpClient;
use Behat\Behat\Context\Context;
use LogicException;
use RuntimeException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\Service\Attribute\Required;

use function is_array;

/**
 * Base Http Client context.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
abstract class BaseContext implements Context
{
    protected Client $client;

    protected string $workingDir;

    #[Required]
    public function setClient(Client $httpTestClient): void
    {
        $this->client = $httpTestClient;
        $this->client->disableReboot();
    }

    #[Required]
    public function setWorkingDir(string $workingDir): void
    {
        $this->workingDir = $workingDir;
    }

    public function getHttpClient(): Client
    {
        try {
            return HttpClient::httpClient();
        } catch (LogicException $e) {
            return HttpClient::httpClient($this->client);
        }
    }

    /**
     * @param array<string,mixed> $options
     */
    public function request(
        string $method,
        string $url,
        array $options = [],
    ): ResponseInterface {
        return $this->getHttpClient()->request(
            $method,
            $url,
            $options,
        );
    }

    public function getResponse(): ResponseInterface
    {
        $response = $this->getHttpClient()->getResponse();
        if (!$response instanceof ResponseInterface) {
            throw new RuntimeException('No Last Response');
        }

        return $response;
    }

    protected function getFilePath(string $filename): string
    {
        return $this->workingDir.'/'.$filename;
    }

    protected function json(string $content): array
    {
        $jsonDecodeResult = json_decode($content, true, flags: \JSON_THROW_ON_ERROR);

        if (!is_array($jsonDecodeResult)) {
            throw new LogicException('json result should be an array.');
        }

        return $jsonDecodeResult;
    }

    protected function getSchemaRefKey(string $schemaRef): string
    {
        return str_replace('#/components/schemas/', '', $schemaRef);
    }
}
