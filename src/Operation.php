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
use LogicException;
use RuntimeException;

abstract class Operation
{
    private ?string $controller = null;
    private ?string $controllerMethod = null;

    /**
     * @param class-string|null  $input
     * @param class-string|null  $output
     * @param array<ApiProperty> $filters
     */
    /** @phpstan-ignore-next-line It's okay to have some unused parameters */
    public function __construct(
        protected readonly string $path,
        protected string|null $name,
        protected readonly string $method,
        protected string|null $input,
        protected string|null $output,
        protected readonly int $statusCode,
        protected string $resource,
        protected array $filters,
        protected readonly bool $openApi,
        protected array $formats,
        protected array $inputFormats,
        protected array $outputFormats,
        protected bool $paginationEnabled,
        protected ?int $paginationItemsPerPage,
        protected ?int $paginationMaximumItemsPerPage,
        protected array $uriVariables,
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
        ?bool $stateless,
    ) {
    }

    public function getControllerMethod(): string
    {
        if ($this->controllerMethod === null) {
            throw new LogicException('Controller method should always be set once the inherited class has been instantiated.');
        }

        return $this->controllerMethod;
    }

    public function setControllerMethod(string $controllerMethod): void
    {
        $this->controllerMethod = $controllerMethod;
    }

    public function getController(): string
    {
        if ($this->controller === null) {
            throw new LogicException('Controller should always be set once the inherited class has been instantiated.');
        }

        return $this->controller;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
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

    public function setOutput(string $output): void
    {
        if (!class_exists($output)) {
            throw new RuntimeException('Given output should be a valid object.');
        }

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

        $this->filters = $filters;
    }

    public function getOpenApi(): bool
    {
        return $this->openApi;
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

    public function getDeprecationReason(): ?string
    {
        return $this->deprecationReason;
    }
}
