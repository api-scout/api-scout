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
use ApiScout\Response\Pagination\PaginationProviderInterface;
use ApiScout\Response\Serializer\ResponseSerializerInterface;
use ApiScout\ResponseGeneratorInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class SerializeResponseListenerTest extends TestCase
{
    public function testDoNotHandleResponse(): void
    {
        $paginationProvider = $this->createStub(PaginationProviderInterface::class);
        $responseSerializer = $this->createStub(ResponseSerializerInterface::class);
        $prepareResponse = $this->createStub(ResponseGeneratorInterface::class);

        $listener = new SerializeResponseListener(
            $paginationProvider,
            $responseSerializer,
            $prepareResponse
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
