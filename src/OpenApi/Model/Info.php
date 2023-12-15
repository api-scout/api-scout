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

final class Info
{
    public function __construct(
        private string $title,
        private string $version,
        private string $description = '',
        private ?string $termsOfService = null,
        private ?Contact $contact = null,
        private ?License $license = null,
        private ?string $summary = null,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTermsOfService(): ?string
    {
        return $this->termsOfService;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function getLicense(): ?License
    {
        return $this->license;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }
}
