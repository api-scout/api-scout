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

namespace ApiScout\Tests\Behat\Core\Http;

use Behat\Gherkin\Node\PyStringNode;
use LogicException;

use function dirname;

final class FileContext extends BaseContext
{
    /**
     * Cleans test folders in the temporary directory.
     *
     * @BeforeSuite
     * @AfterSuite
     */
    public static function cleanTestFolders(): void
    {
        if (is_dir($dir = sys_get_temp_dir().\DIRECTORY_SEPARATOR.'behat')) {
            self::clearDirectory($dir);
        }
    }

    /**
     * Creates a file with specified name and context in current workdir.
     *
     * @Given /^(?:there is )?a file named "([^"]*)" with:$/
     */
    public function aFileNamedWith(string $filename, PyStringNode $content): void
    {
        $content = strtr((string) $content, ["'''" => '"""']);
        $this->createFile($this->workingDir.'/'.$filename, $content);
    }

    /**
     * Creates an empty file with specified name in current workdir.
     *
     * @Given /^(?:there is )?a file named "([^"]*)"$/
     *
     * @param string $filename name of the file (relative path)
     */
    public function aFileNamed(string $filename): void
    {
        $this->createFile($this->workingDir.'/'.$filename, '');
    }

    private function createFile(string $filename, string $content): void
    {
        $path = dirname($filename);
        $this->createDirectory($path);

        file_put_contents($filename, $content);
    }

    private function createDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    private static function clearDirectory(string $path): void
    {
        $files = scandir($path);

        if ($files === false) {
            throw new LogicException('Directory not found at path: '.$path);
        }

        array_shift($files);
        array_shift($files);

        foreach ($files as $file) {
            $file = $path.\DIRECTORY_SEPARATOR.$file;
            if (is_dir($file)) {
                self::clearDirectory($file);
            } else {
                unlink($file);
            }
        }

        rmdir($path);
    }
}
