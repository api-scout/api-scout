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

namespace ApiScout\OpenApi\Model;

use ArrayObject;

final class MediaType
{
    public function __construct(
        private ArrayObject $schema,
        private ?ArrayObject $example = null,
        private ?ArrayObject $examples = null,
        private ?Encoding $encoding = null,
    ) {
    }

    public function getSchema(): ?ArrayObject
    {
        return $this->schema;
    }

    public function getExample(): ?ArrayObject
    {
        return $this->example;
    }

    public function getExamples(): ?ArrayObject
    {
        return $this->examples;
    }

    public function getEncoding(): ?Encoding
    {
        return $this->encoding;
    }

    public function withSchema(ArrayObject $schema): self
    {
        $clone = clone $this;
        $clone->schema = $schema;

        return $clone;
    }

    public function withExample(ArrayObject $example): self
    {
        $clone = clone $this;
        $clone->example = $example;

        return $clone;
    }

    public function withExamples(ArrayObject $examples): self
    {
        $clone = clone $this;
        $clone->examples = $examples;

        return $clone;
    }

    public function withEncoding(Encoding $encoding): self
    {
        $clone = clone $this;
        $clone->encoding = $encoding;

        return $clone;
    }
}
