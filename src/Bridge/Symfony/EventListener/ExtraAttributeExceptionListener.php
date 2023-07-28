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
use Symfony\Component\Serializer\Exception\ExtraAttributesException;

final class ExtraAttributeExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ExtraAttributesException) {
            return;
        }

        $violations = [];

        foreach ($exception->getExtraAttributes() as $attribute) {
            $violations['violations'][] = [
                'path' => $attribute,
                'message' => sprintf('Extra attribute: "%s" is not allowed', $attribute),
            ];
        }

        $event->setResponse(new JsonResponse($violations, Response::HTTP_BAD_REQUEST));
    }
}
