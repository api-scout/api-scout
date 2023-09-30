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

use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

/**
 * LoaderExceptionListener for debugging.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class LoaderExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof LoaderLoadException) {
            return;
        }

        /**
         * @var Throwable $previousException
         */
        $previousException = $exception->getPrevious();

        $event->setResponse(
            new JsonResponse(
                [
                    'error' => $previousException->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            )
        );
    }
}
