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

/**
 * Swagger Ui context configuration.
 *
 * Inspired by ApiPlatform\Symfony\Bundle\SwaggerUi\SwaggerUiAction
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class SwaggerUiContext
{
    public function __construct(
        private readonly bool $swaggerUiEnabled = false,
        private readonly bool $reDocEnabled = false,
        private readonly ?string $assetPackage = null,
        private readonly array $extraConfiguration = []
    ) {
    }

    public function isSwaggerUiEnabled(): bool
    {
        return $this->swaggerUiEnabled;
    }

    public function isRedocEnabled(): bool
    {
        return $this->reDocEnabled;
    }

    public function getAssetPackage(): ?string
    {
        return $this->assetPackage;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtraConfiguration(): array
    {
        return $this->extraConfiguration;
    }
}
