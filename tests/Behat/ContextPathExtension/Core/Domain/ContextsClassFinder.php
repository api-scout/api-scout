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

namespace ApiScout\Tests\Behat\ContextPathExtension\Core\Domain;

use ApiScout\Tests\Behat\ContextPathExtension\Core\Domain\Contracts\Repositories\FileRepositoryInterface;

use function count;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class ContextsClassFinder
{
    private const TOKEN_ID = 0;
    private const TOKEN_STRING = 1;

    public function __construct(private readonly FileRepositoryInterface $fileRepository)
    {
    }

    /**
     * @param array<string> $contexts
     *
     * @return array<string>
     */
    public function buildContexts(array $contexts): array
    {
        $alreadyFormattedClasses = $this->findAlreadyFormattedClasses($contexts);
        $classNameFromFiles = $this->buildClassNamesFromFiles($contexts, $alreadyFormattedClasses);

        return array_unique([...$alreadyFormattedClasses, ...$classNameFromFiles]);
    }

    /**
     * @param array<string, string> $contexts
     *
     * @return array<string>
     */
    private function findAlreadyFormattedClasses(array $contexts): array
    {
        $alreadyFormattedClasses = [];

        foreach ($contexts as $context) {
            if (class_exists($context)) {
                $alreadyFormattedClasses[] = $context;
            }
        }

        return $alreadyFormattedClasses;
    }

    /**
     * @param array<string> $contexts
     * @param array<string> $alreadyFormattedClasses
     *
     * @return array<string>
     */
    private function buildClassNamesFromFiles(array $contexts, array $alreadyFormattedClasses): array
    {
        $files = $this->fileRepository->getFilesWithin(array_diff($contexts, $alreadyFormattedClasses));

        $classNamesFromFiles = [];

        foreach ($files as $file) {
            $className = $this->getFileClassName($file->getContents());

            if (null === $className) {
                continue;
            }

            if (class_exists($className)) {
                $classNamesFromFiles[] = $className;
            }
        }

        return $classNamesFromFiles;
    }

    private function getFileClassName(string $fileContent): ?string
    {
        $tokens = token_get_all($fileContent);
        $numTokens = count($tokens);

        $fileClassName = '';

        for ($i = 0; $i < $numTokens; ++$i) {
            switch ($tokens[$i][self::TOKEN_ID]) {
                case \T_NAMESPACE:
                    $fileClassName = $fileClassName.$tokens[$i + 2][self::TOKEN_STRING];
                    break;
                case \T_ABSTRACT:
                case \T_ENUM:
                case \T_INTERFACE:
                case \T_TRAIT:
                    // We do not want to register as Context those files
                    return null;
                case \T_CLASS:
                    return $fileClassName.'\\'.$tokens[$i + 2][self::TOKEN_STRING];
            }
        }

        return null;
    }
}
