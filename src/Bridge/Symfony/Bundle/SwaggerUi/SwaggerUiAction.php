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

namespace ApiScout\Bridge\Symfony\Bundle\SwaggerUi;

use ApiScout\OpenApi\Factory\OpenApiFactoryInterface;
use ApiScout\OpenApi\OpenApi;
use ApiScout\OpenApi\Options;
use ArrayObject;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Display Swagger ui controller.
 *
 * Inspired by ApiPlatform\Symfony\Bundle\SwaggerUi\SwaggerUiAction
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class SwaggerUiAction
{
    public function __construct(
        private readonly OpenApiFactoryInterface $openApiFactory,
        private readonly SwaggerUiContext $swaggerUiContext,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly NormalizerInterface $apiNormalizer,
        private readonly Options $openApiOptions,
        private readonly ?TwigEnvironment $twig,
    ) {
        if ($twig === null) {
            throw new RuntimeException('The documentation cannot be displayed since the Twig bundle is not installed. Try running "composer require symfony/twig-bundle".');
        }
    }

    /**
     * @throws SyntaxError
     * @throws ExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(Request $request): Response
    {
        $openApi = $this->openApiFactory->__invoke(
            ['base_url' => $request->getBaseUrl() ?: '/'],
        );

        $swaggerContext = [
            'formats' => [],
            'title' => $openApi->getInfo()->getTitle(),
            'description' => $openApi->getInfo()->getDescription(),
            'showWebby' => $this->swaggerUiContext->isWebbyShown(),
            'swaggerUiEnabled' => $this->swaggerUiContext->isSwaggerUiEnabled(),
            'reDocEnabled' => $this->swaggerUiContext->isRedocEnabled(),
            'assetPackage' => $this->swaggerUiContext->getAssetPackage(),
        ];

        $swaggerData = $this->buildSwaggerData($openApi);

        [$swaggerData['path'], $swaggerData['method']] = $this->getPathAndMethod($swaggerData);

        return new Response(
            /** @phpstan-ignore-next-line the check for twig is nullable has already been made here */
            $this->twig->render(
                '@ApiScout/SwaggerUi/index.html.twig',
                $swaggerContext + ['swagger_data' => $swaggerData]
            )
        );
    }

    /**
     * @return array<int, string>
     */
    private function getPathAndMethod(array $swaggerData): array
    {
        if ($swaggerData['spec']['paths'] === []) {
            return ['', ''];
        }

        foreach ($swaggerData['spec']['paths'] as $path => $operations) {
            foreach ($operations as $method => $operation) {
                if ($operation['operationId'] ?? null) {
                    return [$path, $method];
                }
            }
        }
        throw new RuntimeException(sprintf('The operation "%s" cannot be found in the Swagger specification.', $swaggerData['operationId']));
    }

    /**
     * @return array<string, array|ArrayObject|bool|float|int|string|null>
     */
    private function buildSwaggerData(OpenApi $openApi): array
    {
        return [
            'url' => $this->urlGenerator->generate('api_scout_swagger_json', ['format' => 'json']),
            'spec' => $this->apiNormalizer->normalize($openApi, 'json', []),
            'oauth' => [
                'enabled' => $this->openApiOptions->getOAuthEnabled(),
                'type' => $this->openApiOptions->getOAuthType(),
                'flow' => $this->openApiOptions->getOAuthFlow(),
                'tokenUrl' => $this->openApiOptions->getOAuthTokenUrl(),
                'authorizationUrl' => $this->openApiOptions->getOAuthAuthorizationUrl(),
                'scopes' => $this->openApiOptions->getOAuthScopes(),
                'clientId' => '$this->oauthClientId',
                'clientSecret' => '$this->oauthClientSecret',
                'pkce' => '$this->oauthPkce',
            ],
            'extraConfiguration' => $this->swaggerUiContext->getExtraConfiguration(),
        ];
    }
}
