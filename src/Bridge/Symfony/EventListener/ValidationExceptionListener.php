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
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;

use function array_key_exists;

final class ValidationExceptionListener
{
    /**
     * @param array<class-string<\Throwable>, int> $exceptionsToStatuses
     */
    public function __construct(
        private readonly array $exceptionsToStatuses,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception->getPrevious() instanceof ValidationFailedException) {
            return;
        }

        $request = $event->getRequest();
        $operation = $request->attributes->get('_api_scout_operation');

        if (!$operation instanceof HttpOperation) {
            return;
        }

        /**
         * @var ValidationFailedException $validationException
         */
        $validationException = $exception->getPrevious();

        $statusCode = Response::HTTP_BAD_REQUEST;

        $exceptionToStatuses = array_merge(
            $this->exceptionsToStatuses,
            $operation->getExceptionToStatus() ?? [],
        );

        if (isset($exceptionToStatuses[$validationException::class])) {
            $statusCode = $exceptionToStatuses[$validationException::class];
        }

        $violations = $this->formatViolationList($validationException);

        $event->setResponse(new JsonResponse($violations, $statusCode));
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
}
