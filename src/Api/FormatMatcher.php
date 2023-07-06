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

namespace ApiScout\Api;

use function in_array;

/**
 * Matches a mime type to a format.
 *
 * @internal
 */
final class FormatMatcher
{
    /**
     * @var array<string, array<string>>
     */
    private readonly array $formats;

    /**
     * @param array<string, (array<string>|string)> $formats
     */
    public function __construct(array $formats)
    {
        $normalizedFormats = [];
        foreach ($formats as $format => $mimeTypes) {
            $normalizedFormats[$format] = (array) $mimeTypes;
        }
        $this->formats = $normalizedFormats;
    }

    /**
     * Gets the format associated with the mime type.
     *
     * Adapted from {@see \Symfony\Component\HttpFoundation\Request::getFormat}.
     */
    public function getFormat(string $mimeType): ?string
    {
        $canonicalMimeType = null;
        $pos = strpos($mimeType, ';');
        if ($pos !== false) {
            $canonicalMimeType = trim(substr($mimeType, 0, $pos));
        }

        foreach ($this->formats as $format => $mimeTypes) {
            if (in_array($mimeType, $mimeTypes, true)) {
                return $format;
            }
            if ($canonicalMimeType !== null && in_array($canonicalMimeType, $mimeTypes, true)) {
                return $format;
            }
        }

        return null;
    }
}
