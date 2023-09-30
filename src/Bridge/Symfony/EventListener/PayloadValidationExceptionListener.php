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
    private const VIOLATIONS = 'violations';
    private const VALIDATION_PATH = 'path';
    private const VALIDATION_MESSAGE = 'message';



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
            $this->isEmptyPayload($exception) => fn () => $this->emptyPayloadExceptionJsonResponse(),
            $exception instanceof ExtraAttributesException => fn () => $this->extraAttributeExceptionJsonResponse($operation, $exception),
            $exception->getPrevious() instanceof ValidationFailedException => fn () => $this->validationExceptionJsonResponse($operation, $exception),
            default => null,
        };

        if ($jsonResponse === null) {
            return;
        }

        $event->setResponse($jsonResponse());
    }

    private function validationExceptionJsonResponse(Operation $operation, Throwable $exception): JsonResponse
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
            if (array_key_exists('hint', $violation->getParameters())) {
                $violations['message'] = $violation->getParameters()['hint'];

                continue;
            }

            $violations[self::VIOLATIONS][$i++] = [
                self::VALIDATION_PATH => $violation->getPropertyPath(),
                self::VALIDATION_MESSAGE => $violation->getMessage(),
            ];
        }

        return $violations;
    }

    private function emptyPayloadExceptionJsonResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                self::VIOLATIONS => [[
                    self::VALIDATION_PATH => 'payload',
                    self::VALIDATION_MESSAGE => 'Payload should not be empty',
                ]],
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

    private function extraAttributeExceptionJsonResponse(
        Operation $operation,
        ExtraAttributesException $exception
    ): JsonResponse {
        $violations = [];

        foreach ($exception->getExtraAttributes() as $attribute) {
            $violations[self::VIOLATIONS][] = [
                self::VALIDATION_PATH => $attribute,
                self::VALIDATION_MESSAGE => sprintf('Extra attribute: "%s" is not allowed', $attribute),
            ];
        }

        return new JsonResponse(
            $violations,
            $operation->getExceptionToStatusClassStatusCode(
                $this->exceptionsToStatuses,
                $exception,
                Response::HTTP_BAD_REQUEST
            )
        );
    }
}
