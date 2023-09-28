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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\PostDummyFile;

use Symfony\Component\Validator\Constraints as Assert;

final class DummyPayloadFileInput
{
    public function __construct(
        #[Assert\NotBlank()]
        public readonly string $fileName,
    ) {
    }
}
