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
use ApiScout\HttpOperation;
use ApiScout\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Pagination\Paginator;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializeResponseListener
{
    public function __construct(
        private readonly PaginatorRequestFactoryInterface $paginatorRequestFactory,
        private readonly SerializerInterface $serializer,
        private readonly string $responseItemKey
    ) {
    }

    /**
     * Serializes the data to the requested format.
     */
    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $operation = $request->attributes->get('_api_scout_operation');

        if (!$operation instanceof HttpOperation) {
            return;
        }

        $controllerResult = $event->getControllerResult();

        if ($operation instanceof CollectionOperationInterface
            && $operation->isPaginationEnabled()
            && !$controllerResult instanceof Paginator
        ) {
            if (!is_iterable($controllerResult)) {
                throw new LogicException('Controller response from Collection Operation should be iterable.');
            }

            $paginator = new Paginator(
                $controllerResult,
                $this->paginatorRequestFactory->getCurrentPage($request),
                $this->paginatorRequestFactory->getItemsPerPage($operation)
            );

            $event->setResponse(
                new JsonResponse(
                    data: $paginator->toArray(),
                    status: $operation->getStatusCode()
                ),
            );

            return;
        }

        if ($operation instanceof CollectionOperationInterface
            && $operation->isPaginationEnabled()
            && $controllerResult instanceof Paginator
        ) {
            $event->setResponse(
                new JsonResponse(
                    data: $controllerResult->toArray(),
                    status: $operation->getStatusCode()
                ),
            );

            return;
        }

        $event->setResponse(
            new JsonResponse(
                data: $this->serializer->serialize(
                    data: [$this->responseItemKey => $controllerResult],
                    format: 'json',
                    context: $operation->getNormalizationContext()
                ),
                status: $operation->getStatusCode(),
                json: true
            ),
        );
    }
}
