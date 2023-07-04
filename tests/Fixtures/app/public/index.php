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

use ApiScout\Tests\Fixtures\app\AppKernel;

require_once dirname(__DIR__).'/../../../vendor/autoload_runtime.php';

return fn (array $context) => new AppKernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
