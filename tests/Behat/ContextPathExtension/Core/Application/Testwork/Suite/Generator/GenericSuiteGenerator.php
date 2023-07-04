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

namespace ApiScout\Tests\Behat\ContextPathExtension\Core\Application\Testwork\Suite\Generator;

use ApiScout\Tests\Behat\ContextPathExtension\Core\Domain\ContextsClassFinder;
use Behat\Testwork\Suite\Exception\SuiteConfigurationException;
use Behat\Testwork\Suite\Generator\SuiteGenerator;
use Behat\Testwork\Suite\Suite;

use function gettype;
use function is_array;

final class GenericSuiteGenerator implements SuiteGenerator
{
    public function __construct(
        private readonly SuiteGenerator $suiteGenerator,
        private readonly ContextsClassFinder $contextClassFinder
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTypeAndSettings($type, array $settings): bool
    {
        return $this->suiteGenerator->supportsTypeAndSettings($type, $settings);
    }

    /**
     * {@inheritdoc}
     */
    public function generateSuite($suiteName, array $settings): Suite
    {
        $contexts = $settings['contexts'];

        if (!isset($settings['contexts'])) {
            throw new SuiteConfigurationException(sprintf('"contexts" setting of the "%s" suite note found.', $suiteName), $suiteName);
        }

        if (!is_array($contexts)) {
            throw new SuiteConfigurationException(sprintf('"contexts" setting of the "%s" suite is expected to be an array, %s given.', $suiteName, gettype($contexts)), $suiteName);
        }

        $settings['contexts'] = $this->contextClassFinder->buildContexts($contexts);

        return $this->suiteGenerator->generateSuite($suiteName, $settings);
    }
}
