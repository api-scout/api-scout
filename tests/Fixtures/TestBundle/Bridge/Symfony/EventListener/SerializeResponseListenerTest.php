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

namespace ApiScout\Tests\Fixtures\TestBundle\Bridge\Symfony\EventListener;

use ApiScout\Bridge\Symfony\EventListener\SerializeResponseListener;
use ApiScout\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Serializer\ResponseSerializerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializeResponseListenerTest extends TestCase
{
    public function testDoNotHandleResponse(): void
    {
        $paginatorRequestFactory = $this->createStub(PaginatorRequestFactoryInterface::class);
        $serializer = $this->createStub(ResponseSerializerInterface::class);

        $listener = new SerializeResponseListener(
            $paginatorRequestFactory,
            $serializer
        );

        $event = new ViewEvent(
            $this->createStub(HttpKernelInterface::class),
            new Request(),
            HttpKernelInterface::MAIN_REQUEST,
            null
        );

        $listener->onKernelView($event);

        Assert::assertNull($event->getResponse());
    }
}
