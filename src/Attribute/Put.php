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

namespace ApiScout\Attribute;

use ApiScout\OpenApi\Http\Abstract\HttpRequest;
use ApiScout\OpenApi\Http\Abstract\HttpResponse;
use ApiScout\OpenApi\Model\Operation as OpenApiOperation;
use ApiScout\Operation;
use Attribute;

/**
 * Put Operation.
 *
 * Inspired by ApiPlatform\Metadata\Put
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
final class Put extends Operation
{
    public function __construct(
        string $path,
        ?string $name = null,
        ?string $input = null,
        ?string $output = null,
        int $statusCode = HttpResponse::HTTP_OK,
        string $resource = 'Default',
        array $filters = [],
        bool|OpenApiOperation|null $openapi = null,
        ?array $exceptionToStatus = null,
        array $formats = [],
        array $inputFormats = [
            'json' => ['application/json'],
            'html' => ['text/html'],
        ],
        array $outputFormats = [
            'json' => ['application/json'],
            'html' => ['text/html'],
        ],
        bool $paginationEnabled = false,
        ?int $paginationItemsPerPage = null,
        ?int $paginationMaximumItemsPerPage = null,
        array $uriVariables = [],
        array $normalizationContext = [],
        array $denormalizationContext = [],
        ?string $deprecationReason = null,
        array $requirements = [],
        array $options = [],
        array $defaults = [],
        ?string $host = null,
        array|string $schemes = [],
        ?string $condition = null,
        ?int $priority = null,
        ?string $locale = null,
        ?string $format = null,
        ?bool $stateless = null,
    ) {
        parent::__construct(
            path: $path,
            name: $name,
            method: HttpRequest::METHOD_PUT,
            input: $input,
            output: $output,
            statusCode: $statusCode,
            resource: $resource,
            filters: $filters,
            openapi: $openapi,
            exceptionToStatus: $exceptionToStatus,
            formats: $formats,
            inputFormats: $inputFormats,
            outputFormats: $outputFormats,
            paginationEnabled: $paginationEnabled,
            paginationItemsPerPage: $paginationItemsPerPage,
            paginationMaximumItemsPerPage: $paginationMaximumItemsPerPage,
            uriVariables: $uriVariables,
            normalizationContext: $normalizationContext,
            denormalizationContext: $denormalizationContext,
            deprecationReason: $deprecationReason,
            requirements: $requirements,
            options: $options,
            defaults: $defaults,
            host: $host,
            schemes: $schemes,
            condition: $condition,
            priority: $priority,
            locale: $locale,
            format: $format,
            stateless: $stateless,
        );
    }
}
