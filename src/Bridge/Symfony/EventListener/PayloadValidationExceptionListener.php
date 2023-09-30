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
            $this->isEmptyPayload($exception) => fn () => $this->emptyPayloadViolations(),
            $exception instanceof ExtraAttributesException => fn () => $this->extraAttributeViolations($exception),
            $exception->getPrevious() instanceof ValidationFailedException => fn () => $this->validationViolations($exception),
            default => null,
        };

        if ($jsonResponse === null) {
            return;
        }

        $event->setResponse(new JsonResponse(
            data: $jsonResponse(),
            status: $operation->getExceptionToStatusClassStatusCode(
                $this->exceptionsToStatuses,
                $exception->getPrevious() instanceof ValidationFailedException ? $exception->getPrevious() : $exception,
                Response::HTTP_BAD_REQUEST
            ),
            headers: [
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'deny',
            ]
        ));
    }

    private function validationViolations(Throwable $exception): array
    {
        /**
         * @var ValidationFailedException $validationException
         */
        $validationException = $exception->getPrevious();

        return $this->formatViolationList($validationException);
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

    /**
     * @return array<string, array<array<string, string>>>
     */
    private function emptyPayloadViolations(): array
    {
        return [
            self::VIOLATIONS => [[
                self::VALIDATION_PATH => 'payload',
                self::VALIDATION_MESSAGE => 'Payload should not be empty',
            ]],
        ];
    }

    private function isEmptyPayload(Throwable $exception): bool
    {
        return $exception instanceof HttpException
            && $exception->getPrevious() === null
            && $exception->getStatusCode() === Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    /**
     * @return array<string, array<array<string, string>>>
     */
    private function extraAttributeViolations(ExtraAttributesException $exception): array
    {
        $violations = [];

        foreach ($exception->getExtraAttributes() as $attribute) {
            $violations[self::VIOLATIONS][] = [
                self::VALIDATION_PATH => $attribute,
                self::VALIDATION_MESSAGE => sprintf('Extra attribute: "%s" is not allowed', $attribute),
            ];
        }

        return $violations;
    }
}
