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

namespace ApiScout\Bridge\Symfony\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

/**
 * Format Custom Exception for the Symfony Error.
 *
 * @author Jules Pietri <jules@heahprod.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class CustomExceptionListener implements EventSubscriberInterface
{
    /**
     * @param array<class-string<Throwable>, int> $exceptionsToStatuses
     */
    public function __construct(
        private readonly array $exceptionsToStatuses,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['customizeThrowable', -64]];
    }

    public function customizeThrowable(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable()->getPrevious();

        if (isset($this->exceptionsToStatuses[$exception::class])) {
            $event->setThrowable(new HttpException($this->exceptionsToStatuses[$exception::class]));
            $event->allowCustomResponseCode();
        }
    }
}
