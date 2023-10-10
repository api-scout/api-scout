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

namespace ApiScout\Resource;

use FilesystemIterator;
use InvalidArgumentException;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use function count;
use function defined;
use function in_array;

/**
 * Extract class name from files.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class DirectoryClassesExtractor
{
    public function __construct(
        private readonly array $paths
    ) {
    }

    public function extract(): array
    {
        $files = [];

        foreach ($this->paths as $path) {
            $files += iterator_to_array(new RecursiveIteratorIterator(
                new RecursiveCallbackFilterIterator(
                    new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS),
                    static fn (SplFileInfo $current) => !str_starts_with($current->getBasename(), '.')
                ),
                RecursiveIteratorIterator::LEAVES_ONLY
            ));
        }

        /** @phpstan-ignore-next-line Specified type are okay */
        usort($files, static fn (SplFileInfo $a, SplFileInfo $b) => (string) $a > (string) $b ? 1 : -1);

        $classes = [];
        /**
         * @var array<SplFileInfo> $files
         */
        foreach ($files as $file) {
            if (!$file->isFile() || !str_ends_with($file->getFilename(), '.php')) {
                continue;
            }

            if (($class = self::buildClass((string) $file)) !== false) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    private function buildClass(string $file): string|false
    {
        $class = false;
        $namespace = false;
        $fileContent = file_get_contents($file);

        if ($fileContent === false) {
            return false;
        }

        $tokens = token_get_all($fileContent);

        if (count($tokens) === 1 && $tokens[0][0] === \T_INLINE_HTML) {
            throw new InvalidArgumentException(sprintf('The file "%s" does not contain PHP code. Did you forgot to add the "<?php" start tag at the beginning of the file?', $file));
        }

        $nsTokens = [\T_NS_SEPARATOR => true, \T_STRING => true];
        if (defined('T_NAME_QUALIFIED')) {
            $nsTokens[\T_NAME_QUALIFIED] = true;
        }
        for ($i = 0; isset($tokens[$i]); ++$i) {
            $token = $tokens[$i];
            if (!isset($token[1])) {
                continue;
            }

            if ($class === true && $token[0] === \T_STRING) {
                return $namespace.'\\'.$token[1];
            }

            if ($namespace === true && isset($nsTokens[$token[0]])) {
                $namespace = $token[1];
                while (isset($tokens[++$i][1], $nsTokens[$tokens[$i][0]])) {
                    $namespace .= $tokens[$i][1];
                }
                $token = $tokens[$i];
            }

            if ($token[0] === \T_CLASS) {
                // Skip usage of ::class constant and anonymous classes
                $skipClassToken = false;
                for ($j = $i - 1; $j > 0; --$j) {
                    if (!isset($tokens[$j][1])) {
                        if ($tokens[$j] === '(' || $tokens[$j] === ',') {
                            $skipClassToken = true;
                        }
                        break;
                    }

                    if ($tokens[$j][0] === \T_DOUBLE_COLON || $tokens[$j][0] === \T_NEW) {
                        $skipClassToken = true;
                        break;
                    }
                    if (!in_array($tokens[$j][0], [\T_WHITESPACE, \T_DOC_COMMENT, \T_COMMENT], true)) {
                        break;
                    }
                }

                if (!$skipClassToken) {
                    $class = true;
                }
            }

            if ($token[0] === \T_NAMESPACE) {
                $namespace = true;
            }
        }

        return false;
    }
}
