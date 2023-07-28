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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DeleteDummy;

use ApiScout\Attribute\Delete;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DeleteDummyController extends AbstractController
{
    #[Delete('/dummies/{name}', resource: Dummy::class)]
    public function __invoke(string $name): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
