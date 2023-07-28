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

namespace ApiScout\Tests\Behat\ContextPathExtension\Core\Infrastructure\Repositories;

use ApiScout\Tests\Behat\ContextPathExtension\Core\Domain\Contracts\Repositories\FileRepositoryInterface;
use Symfony\Component\Finder\Finder;

final class LocalFileRepository implements FileRepositoryInterface
{
    public const DEFAULT_EXCLUDE = ['vendor', 'tests', 'Tests', 'test', 'Test'];

    private Finder $finder;

    /**
     * Get the files.
     *
     * @param array<string> $paths
     *
     * @return array<string, \Symfony\Component\Finder\SplFileInfo>
     */
    public function getFilesWithin(array $paths): array
    {
        $this->finder = Finder::create()
            ->files()
            ->in($paths)
            ->name(['*.php'])
            ->exclude(self::DEFAULT_EXCLUDE)
            ->ignoreUnreadableDirs()
        ;

        return $this->getFilesList();
    }

    /**
     * @return array<\Symfony\Component\Finder\SplFileInfo>
     */
    private function getFilesList(): array
    {
        return iterator_to_array($this->finder->getIterator());
    }
}
