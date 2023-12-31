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

namespace ApiScout;

use ApiScout\Attribute\ApiProperty;
use ApiScout\Exception\FiltersShouldBeAnArrayOfApiPropertyException;
use ApiScout\Exception\ResourceClassNotFoundException;
use ApiScout\Exception\UriVariablesShouldBeAnArrayOfApiPropertyException;
use ApiScout\OpenApi\Model\Operation as OpenApiOperation;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Attribute to build the Operation.
 *
 * Inspired by ApiPlatform\Metadata\Operation
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
abstract class Operation extends Route
{
    /**
     * @param class-string|null  $input
     * @param class-string|null  $output
     * @param array<ApiProperty> $filters
     */
    public function __construct(
        protected readonly string $path,
        protected string|null $name,
        protected readonly string $method,
        protected string|null $input,
        protected string|null $output,
        protected readonly int $statusCode,
        protected string $resource,
        protected array $filters,
        protected readonly bool|OpenApiOperation|null $openapi,
        protected readonly ?array $exceptionToStatus,
        protected array $formats,
        protected array $inputFormats,
        protected array $outputFormats,
        protected bool $paginationEnabled,
        protected ?int $paginationItemsPerPage,
        protected ?int $paginationMaximumItemsPerPage,
        protected array $uriVariables,
        protected array $normalizationContext,
        protected array $denormalizationContext,
        protected ?string $deprecationReason,
        array $requirements,
        array $options,
        array $defaults,
        ?string $host,
        array|string $schemes,
        ?string $condition,
        ?int $priority,
        ?string $locale,
        ?string $format,
        ?bool $utf8 = null,
        ?bool $stateless = null,
        ?string $env = null,
    ) {
        parent::__construct(
            path: $path,
            name: $name,
            requirements: $requirements,
            options: $options,
            defaults: $defaults,
            host: $host,
            methods: [$method],
            schemes: $schemes,
            condition: $condition,
            priority: $priority,
            locale: $locale,
            format: $format,
            utf8: $utf8,
            stateless: $stateless,
            env: $env,
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function setInput(string $input): void
    {
        if (!class_exists($input)) {
            throw new ResourceClassNotFoundException($input);
        }

        $this->input = $input;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    /**
     * @param class-string|null $output
     */
    public function setOutput(?string $output): void
    {
        $this->output = $output;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @return array<ApiProperty>
     */
    public function getFilters(): array
    {
        foreach ($this->filters as $filter) {
            /**
             * @phpstan-ignore-next-line since we can build this property through
             * the constructor this value must be checked.
             * Idealistically this check should be done in the constructor
             */
            if (!$filter instanceof ApiProperty) {
                throw new FiltersShouldBeAnArrayOfApiPropertyException($filter);
            }
        }

        return $this->filters;
    }

    /**
     * @param array<ApiProperty|mixed> $filters
     */
    public function setFilters(array $filters): void
    {
        foreach ($filters as $filter) {
            if (!$filter instanceof ApiProperty) {
                throw new FiltersShouldBeAnArrayOfApiPropertyException($filter);
            }
        }

        /** @phpstan-ignore-next-line phpstan does not understand that once here, this is and array of ApiProperty */
        $this->filters = $filters;
    }

    public function getOpenapi(): bool|OpenApiOperation|null
    {
        return $this->openapi;
    }

    public function getExceptionToStatus(): ?array
    {
        return $this->exceptionToStatus;
    }

    /**
     * @param array<class-string<Throwable>, int> $exceptionToStatus
     */
    public function getExceptionToStatusClassStatusCode(
        array $exceptionToStatus,
        object $classException,
        int $defaultStatusCode = 400,
    ): int {
        $exceptionToStatuses = $this->formatExceptionToStatusWithConfiguration($exceptionToStatus);

        if (isset($exceptionToStatuses[$classException::class])) {
            return $exceptionToStatuses[$classException::class];
        }

        return $defaultStatusCode;
    }

    public function getFormats(): array
    {
        return $this->formats;
    }

    public function getInputFormats(): array
    {
        return $this->inputFormats;
    }

    public function getOutputFormats(): array
    {
        return $this->outputFormats;
    }

    public function isPaginationEnabled(): bool
    {
        return $this->paginationEnabled;
    }

    public function setIsPaginationEnabled(bool $isPaginationEnabled): void
    {
        $this->paginationEnabled = $isPaginationEnabled;
    }

    public function getPaginationItemsPerPage(): ?int
    {
        return $this->paginationItemsPerPage;
    }

    public function getPaginationMaximumItemsPerPage(): ?int
    {
        return $this->paginationMaximumItemsPerPage;
    }

    public function getUriVariables(): array
    {
        foreach ($this->uriVariables as $uriVariable) {
            if (!$uriVariable instanceof ApiProperty) {
                throw new UriVariablesShouldBeAnArrayOfApiPropertyException($uriVariable);
            }
        }

        return $this->uriVariables;
    }

    public function setUriVariables(array $uriVariables): void
    {
        foreach ($uriVariables as $uriVariable) {
            if (!$uriVariable instanceof ApiProperty) {
                throw new UriVariablesShouldBeAnArrayOfApiPropertyException($uriVariable);
            }
        }

        $this->uriVariables = $uriVariables;
    }

    public function getNormalizationContext(): array
    {
        return $this->normalizationContext;
    }

    public function getDenormalizationContext(): array
    {
        return $this->denormalizationContext;
    }

    public function setDenormalizationContext(array $denormalizationContext): void
    {
        $this->denormalizationContext = $denormalizationContext;
    }

    public function getDeprecationReason(): ?string
    {
        return $this->deprecationReason;
    }

    /**
     * @param array<class-string<Throwable>, int> $exceptionToStatus
     *
     * @return array<class-string<Throwable>, int>
     */
    public function formatExceptionToStatusWithConfiguration(array $exceptionToStatus): array
    {
        return array_merge(
            $exceptionToStatus,
            $this->exceptionToStatus ?? [],
        );
    }
}
