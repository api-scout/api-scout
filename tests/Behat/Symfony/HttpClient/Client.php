<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiScout\Tests\Behat\Symfony\HttpClient;

use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\HttpClientTrait;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

use function in_array;
use function is_array;
use function json_decode;
use function json_encode;

final class Client implements HttpClientInterface
{
    use HttpClientTrait;

    /**
     * @see HttpClientInterface::OPTIONS_DEFAULTS
     */
    public const API_OPTIONS_DEFAULTS = [
        'auth_basic' => null,
        'auth_bearer' => null,
        'query' => [],
        'headers' => ['accept' => ['application/json']],
        'body' => '',
        'json' => null,
        'base_uri' => 'https://example.com',
        'extra' => [],
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $defaultOptions = self::API_OPTIONS_DEFAULTS;

    private KernelBrowser $kernelBrowser;

    private Response $response;

    /**
     * @param array $defaultOptions Default options for the requests
     *
     * @see HttpClientInterface::OPTIONS_DEFAULTS for available options
     */
    public function __construct(
        KernelBrowser $kernelBrowser,
        array $defaultOptions = []
    ) {
        $this->kernelBrowser = $kernelBrowser;
        $kernelBrowser->followRedirects(false);

        if ($defaultOptions !== []) {
            $this->setDefaultOptions($defaultOptions);
        }
    }

    /**
     * Sets the default options for the requests.
     *
     * @see HttpClientInterface::OPTIONS_DEFAULTS for available options
     */
    public function setDefaultOptions(array $defaultOptions): void
    {
        [, $this->defaultOptions] = self::prepareRequest(null, null, $defaultOptions, self::API_OPTIONS_DEFAULTS);
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $basic = $options['auth_basic'] ?? null;

        [$url, $options] = self::prepareRequest($method, $url, $options, $this->defaultOptions);

        $resolvedUrl = implode('', $url);
        $server = [];

        // Convert headers to a $_SERVER-like array
        foreach (self::extractHeaders($options) as $key => $value) {
            $normalizedHeaderName = strtoupper(str_replace('-', '_', $key));
            $header = in_array($normalizedHeaderName, ['CONTENT_TYPE', 'REMOTE_ADDR'], true) ? $normalizedHeaderName : sprintf('HTTP_%s', $normalizedHeaderName);
            // BrowserKit doesn't support setting several headers with the same name
            $server[$header] = $value[0] ?? '';
        }

        if ($basic) {
            $credentials = is_array($basic) ? $basic : explode(':', $basic, 2);
            $server['PHP_AUTH_USER'] = $credentials[0];
            $server['PHP_AUTH_PW'] = $credentials[1] ?? '';
        }

        $info = [
            'response_headers' => [],
            'redirect_count' => 0,
            'redirect_url' => null,
            'start_time' => 0.0,
            'http_method' => $method,
            'http_code' => 0,
            'error' => null,
            'user_data' => $options['user_data'] ?? null,
            'url' => $resolvedUrl,
            'primary_port' => $url['scheme'] === 'http:' ? 80 : 443,
        ];

        if (isset($_SERVER['TESTS_HTTP_CLIENT_DEBUG']) && (bool) $_SERVER['TESTS_HTTP_CLIENT_DEBUG'] === true) {
            foreach ($server as $serverHeaderField => $serverHeaderFieldValue) {
                echo sprintf(
                    '%s: %s',
                    $serverHeaderField,
                    $serverHeaderFieldValue
                );
                echo "\n";
            }
            echo "\n";
            echo sprintf(
                "%s %s \n%s",
                $method,
                $resolvedUrl,
                $options['body'] ? json_encode(json_decode($options['body']), \JSON_PRETTY_PRINT) : ''
            );
        }

        $this->kernelBrowser->request(
            $method,
            $resolvedUrl,
            $options['extra']['parameters'] ?? [],
            $options['extra']['files'] ?? [],
            $server,
            $options['body'] ?? null
        );

        $this->response = new Response(
            $this->kernelBrowser->getResponse(),
            $this->kernelBrowser->getInternalResponse(),
            $info
        );

        if (isset($_SERVER['TESTS_HTTP_CLIENT_DEBUG']) && (bool) $_SERVER['TESTS_HTTP_CLIENT_DEBUG'] === true) {
            foreach ($this->response->getHeaders(false) as $responseHeaderField => $responseHeaderFieldValue) {
                echo sprintf(
                    '%s: %s',
                    $responseHeaderField,
                    implode(',', $responseHeaderFieldValue)
                );
                echo "\n";
            }
            echo "\n";

            if ($this->response->getContent(false) !== '') {
                echo sprintf(
                    "\n%s %s\n%s",
                    $this->response->getStatusCode(),
                    SymfonyResponse::$statusTexts[$this->response->getStatusCode()],
                    json_encode($this->response->toArray(false), \JSON_PRETTY_PRINT)
                );
            }
        }

        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function stream($responses, ?float $timeout = null): ResponseStreamInterface
    {
        throw new LogicException('Not implemented yet');
    }

    /**
     * Gets the latest response.
     *
     * @internal
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Gets the underlying test client.
     *
     * @internal
     */
    public function getKernelBrowser(): KernelBrowser
    {
        return $this->kernelBrowser;
    }

    // The following methods are proxy methods for KernelBrowser's ones

    /**
     * Returns the container.
     *
     * @return ContainerInterface|null Returns null when the Kernel has been shutdown or not started yet
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->kernelBrowser->getContainer();
    }

    /**
     * Returns the CookieJar instance.
     */
    public function getCookieJar(): CookieJar
    {
        return $this->kernelBrowser->getCookieJar();
    }

    /**
     * Returns the kernel.
     */
    public function getKernel(): KernelInterface
    {
        return $this->kernelBrowser->getKernel();
    }

    /**
     * Gets the profile associated with the current Response.
     */
    public function getProfile(): Profile|false|null
    {
        return $this->kernelBrowser->getProfile();
    }

    /**
     * Enables the profiler for the very next request.
     *
     * If the profiler is not enabled, the call to this method does nothing.
     */
    public function enableProfiler(): void
    {
        $this->kernelBrowser->enableProfiler();
    }

    /**
     * Disables kernel reboot between requests.
     *
     * By default, the Client reboots the Kernel for each request. This method
     * allows to keep the same kernel across requests.
     */
    public function disableReboot(): void
    {
        $this->kernelBrowser->disableReboot();
    }

    /**
     * Enables kernel reboot between requests.
     */
    public function enableReboot(): void
    {
        $this->kernelBrowser->enableReboot();
    }

    /**
     * Extracts headers depending on the symfony/http-client version being used.
     *
     * @return array<string, array<string>>
     */
    private static function extractHeaders(array $options): array
    {
        if (!isset($options['normalized_headers'])) {
            return $options['headers'];
        }

        $headers = [];

        /** @var string $key */
        foreach ($options['normalized_headers'] as $key => $values) {
            foreach ($values as $value) {
                [, $value] = explode(': ', $value, 2);
                $headers[$key][] = $value;
            }
        }

        return $headers;
    }
}
