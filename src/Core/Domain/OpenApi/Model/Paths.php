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

namespace ApiScout\Core\Domain\OpenApi\Model;

final class Paths
{
    /**
     * @var array<string, PathItem>
     */
    private array $paths = [];

    public function addPath(string $path, PathItem $pathItem): void
    {
        $this->paths[$path] = $pathItem;

        ksort($this->paths);
    }

    public function getPath(string $path): ?PathItem
    {
        return $this->paths[$path] ?? null;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }
}
