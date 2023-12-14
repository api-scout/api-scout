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

use ApiScout\Attribute\CollectionOperationInterface;
use ApiScout\Operation;
use ApiScout\Response\Pagination\PaginationInterface;
use ApiScout\Response\Pagination\PaginationProviderInterface;
use ApiScout\Response\Pagination\QueryInput\PaginationQueryInputInterface;
use ApiScout\Response\ResponseGeneratorInterface;
use ApiScout\Response\Serializer\Normalizer\NormalizerInterface;
use ApiScout\Response\Serializer\Serializer\ResponseSerializerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

use function is_object;

/**
 * Add the proper Operation to the request for further handling.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class SerializeResponseListener
{
    public function __construct(
        private readonly PaginationProviderInterface $paginationProvider,
        private readonly ResponseSerializerInterface $responseSerializer,
        private readonly ResponseGeneratorInterface $responseGenerator,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * Serializes the data to the requested format.
     */
    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $operation = $request->attributes->get('_api_scout_operation');

        if (!$operation instanceof Operation) {
            return;
        }

        $data = $event->getControllerResult();

        if (!is_iterable($data) && !is_object($data) && !$data instanceof PaginationInterface) {
            return;
        }

        if ($operation instanceof CollectionOperationInterface && $operation->isPaginationEnabled()) {
            $data = $this->paginationProvider->provide(
                $data,
                $operation,
                $this->getPaginationQueryInput($event),
            );
        }

        $data = $this->normalizer->normalize($data, $operation);

        $event->setResponse(
            new JsonResponse(
                data: $this->responseSerializer->serialize(
                    data: $this->responseGenerator->generate($data, $operation),
                    context: $operation->getNormalizationContext(),
                ),
                status: $operation->getStatusCode(),
                json: true,
            ),
        );
    }

    private function getPaginationQueryInput(ViewEvent $event): PaginationQueryInputInterface
    {
        if (null === $event->controllerArgumentsEvent) {
            throw new RuntimeException('Pagination cannot be enabled without implementing PaginationQueryInputInterface.');
        }

        foreach ($event->controllerArgumentsEvent->getArguments() as $argument) {
            if ($argument instanceof PaginationQueryInputInterface) {
                return $argument;
            }
        }

        throw new RuntimeException('Pagination cannot be enabled without implementing PaginationQueryInputInterface.');
    }
}
