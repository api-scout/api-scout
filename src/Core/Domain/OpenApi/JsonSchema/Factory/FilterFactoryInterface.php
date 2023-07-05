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

namespace ApiScout\Core\Domain\OpenApi\JsonSchema\Factory;

use ApiScout\Core\Domain\Attribute\ApiProperty;
use ApiScout\Core\Domain\OpenApi\Model;

interface FilterFactoryInterface
{
    public function buildPathFilter(array $uriVariables, Model\Operation $openapiOperation): Model\Operation;

    /**
     * @param array<ApiProperty> $operationFilters
     */
    public function buildQueryFilters(array $operationFilters, Model\Operation $openapiOperation): Model\Operation;
}
