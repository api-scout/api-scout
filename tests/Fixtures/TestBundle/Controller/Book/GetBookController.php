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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Book;

use ApiScout\Attribute\Get;

final class GetBookController
{
    #[Get('/books')]
    public function __invoke(string $id): BookOutput
    {
        return new BookOutput(
            'PHPStan',
            'There is only one level with PHPStan. The level max.',
        );
    }
}
