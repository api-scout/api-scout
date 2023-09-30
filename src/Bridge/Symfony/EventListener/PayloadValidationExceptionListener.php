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

use ApiScout\HttpOperation;
use ApiScout\Operation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

use function array_key_exists;

final class PayloadValidationExceptionListener
{
    /**
     * @param array<class-string<Throwable>, int> $exceptionsToStatuses
     */
    public function __construct(
        private readonly array $exceptionsToStatuses,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $request = $event->getRequest();
        $operation = $request->attributes->get('_api_scout_operation');

        if (!$operation instanceof HttpOperation) {
            return;
        }

        $jsonResponse = match (true) {
            $this->isEmptyPayload($exception) => fn () => $this->emptyPayloadException(),
            $exception instanceof ExtraAttributesException => fn () => $this->extraAttributeException($exception),
            $exception->getPrevious() instanceof ValidationFailedException => fn () => $this->validationException($exception, $operation),
            default => null,
        };

        if ($jsonResponse === null) {
            return;
        }

        $event->setResponse(
            $jsonResponse()
        );
    }

    private function validationException(Throwable $exception, Operation $operation): JsonResponse
    {
        /**
         * @var ValidationFailedException $validationException
         */
        $validationException = $exception->getPrevious();

        $violations = $this->formatViolationList($validationException);

        return new JsonResponse(
            $violations,
            $operation->getExceptionToStatusClassStatusCode(
                $this->exceptionsToStatuses,
                $validationException,
                Response::HTTP_BAD_REQUEST
            )
        );
    }

    /**
     * @return array<array>|array{violations: string, array{path: string, message: string}}
     */
    private function formatViolationList(ValidationFailedException $validationException): array
    {
        /**
         * @var array<ConstraintViolation> $violationListException
         */
        $violationListException = $validationException->getViolations();
        $violations = [];
        $i = 0;

        foreach ($violationListException as $violation) {
            $violations['violations'][$i] = [
                'path' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];

            if (array_key_exists('hint', $violation->getParameters())) {
                $violations['violations'][$i] += ['hint' => $violation->getParameters()['hint']];
            }

            ++$i;
        }

        return $violations;
    }

    private function emptyPayloadException(): JsonResponse
    {
        return new JsonResponse(
            [
                'violations' => [[
                    'path' => 'payload',
                    'message' => 'Payload should not be empty',
                ],
                ],
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    private function isEmptyPayload(Throwable $exception): bool
    {
        return $exception instanceof HttpException
            && $exception->getPrevious() === null
            && $exception->getStatusCode() === Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    private function extraAttributeException(ExtraAttributesException $exception): JsonResponse
    {
        $violations = [];

        foreach ($exception->getExtraAttributes() as $attribute) {
            $violations['violations'][] = [
                'path' => $attribute,
                'message' => sprintf('Extra attribute: "%s" is not allowed', $attribute),
            ];
        }

        return new JsonResponse(
            $violations,
            Response::HTTP_BAD_REQUEST
        );
    }
}
