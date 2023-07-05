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

namespace ApiScout\Tests\Fixtures\TestBundle\Core\Infrastructure\Symfony\EventListener;

use ApiScout\Core\Domain\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Core\Domain\Resource\Factory\ResourceCollectionFactoryInterface;
use ApiScout\Core\Infrastructure\Symfony\EventListener\SerializeResponseListener;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function defined;

final class SerializeResponseListenerTest extends TestCase
{
    public function testDoNotHandleResponse(): void
    {
        $resourceCollectionFactory = $this->createStub(ResourceCollectionFactoryInterface::class);
        $paginatorRequestFactory = $this->createStub(PaginatorRequestFactoryInterface::class);
        $normalizer = $this->createStub(NormalizerInterface::class);

        $listener = new SerializeResponseListener(
            $resourceCollectionFactory,
            $paginatorRequestFactory,
            $normalizer
        );

        $event = new ViewEvent(
            $this->createStub(HttpKernelInterface::class),
            new Request(),
            defined(HttpKernelInterface::class.'::MAIN_REQUEST') ? HttpKernelInterface::MAIN_REQUEST : HttpKernelInterface::MASTER_REQUEST,
            null
        );

        $listener->onKernelView($event);

        Assert::assertNull($event->getResponse());
    }
}
