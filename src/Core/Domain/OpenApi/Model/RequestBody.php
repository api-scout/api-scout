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

use ArrayObject;
use LogicException;

final class RequestBody
{
    public function __construct(
        private string $description = '',
        private ?ArrayObject $content = null,
        private bool $required = false
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContent(): ArrayObject
    {
        if ($this->content === null) {
            throw new LogicException('RequestBody content should not be null');
        }

        return $this->content;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function withDescription(string $description): self
    {
        $clone = clone $this;
        $clone->description = $description;

        return $clone;
    }

    public function withContent(ArrayObject $content): self
    {
        $clone = clone $this;
        $clone->content = $content;

        return $clone;
    }

    public function withRequired(bool $required): self
    {
        $clone = clone $this;
        $clone->required = $required;

        return $clone;
    }
}
