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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class EmptyPayloadExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$this->isEmptyPayload($exception)) {
            return;
        }

        $event->setResponse(new JsonResponse(
            [
                'violations' => [[
                    'path' => 'payload',
                    'message' => 'Payload should not be empty',
                ],
                ],
            ],
            Response::HTTP_BAD_REQUEST
        ));
    }

    private function isEmptyPayload(Throwable $exception): bool
    {
        return $exception instanceof HttpException
            && $exception->getPrevious() === null
            && $exception->getStatusCode() === Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}
