<?php

declare(strict_types=1);

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiScout\Core\Domain;

abstract class HttpOperation extends Operation
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';

    public function __construct(
        string $path,
        string $name,
        string $method,
        string|null $input,
        string|null $output,
        int $statusCode,
        string $class,
        ?array $filters,
        bool $openApi,
        array $formats,
        array $inputFormats,
        array $outputFormats,
        bool $paginationEnabled,
        ?int $paginationItemsPerPage,
        ?int $paginationMaximumItemsPerPage,
        protected array $uriVariables,
        ?string $deprecationReason,
        private readonly array $requirements,
        private readonly array $options,
        private readonly array $defaults,
        private readonly ?string $host,
        private readonly array|string $schemes,
        private readonly ?string $condition,
        private readonly ?int $priority,
        private readonly ?string $locale,
        private readonly ?string $format,
        private readonly ?bool $stateless,
    ) {
        parent::__construct(
            path: $path,
            name: $name,
            method: $method,
            input: $input,
            output: $output,
            statusCode: $statusCode,
            class: $class,
            filters: $filters,
            openApi: $openApi,
            formats: $formats,
            inputFormats: $inputFormats,
            outputFormats: $outputFormats,
            paginationEnabled: $paginationEnabled,
            paginationItemsPerPage: $paginationItemsPerPage,
            paginationMaximumItemsPerPage: $paginationMaximumItemsPerPage,
            uriVariables: $uriVariables,
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
            stateless: $stateless
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getSchemes(): array|string
    {
        return $this->schemes;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getStateless(): ?bool
    {
        return $this->stateless;
    }
}
