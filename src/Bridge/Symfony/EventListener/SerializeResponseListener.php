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
use ApiScout\Operation;
use ApiScout\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Pagination\PaginationInterface;
use ApiScout\Pagination\Paginator;
use ApiScout\Pagination\PaginatorInterface;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Add the proper Operation to the request for further handling.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
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

        if ($operation instanceof CollectionOperationInterface) {
            $event->setResponse(
                $this->handleCollectionOperationResponse(
                    $operation,
                    $controllerResult,
                    $request
                )
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

    private function handleCollectionOperationResponse(
        Operation $operation,
        mixed $controllerResult,
        Request $request
    ): JsonResponse {
//        if (!$operation->isPaginationEnabled()) {
//            return new JsonResponse(
//                data: $controllerResult,
//                status: $operation->getStatusCode()
//            );
//        }
//
//        if (!$controllerResult instanceof PaginatorInterface) {
//            if (!is_iterable($controllerResult)) {
//                throw new LogicException('Controller response from Collection Operation should be iterable.');
//            }
//
//            $paginator = new Paginator(
//                $controllerResult,
//                $this->paginatorRequestFactory->getCurrentPage($request),
//                $this->paginatorRequestFactory->getItemsPerPage($operation)
//            );
//
//            return new JsonResponse(
//                data: $paginator->toArray(),
//                status: $operation->getStatusCode()
//            );
//        }

        if ($controllerResult instanceof PaginationInterface) {
            $controllerResult = [
                $this->responseItemKey => $controllerResult->getItems(),
                'pagination' => $controllerResult->getMetadata()
            ];
        }

        return new JsonResponse(
            data: $this->serializer->serialize(
                data: $controllerResult,
                format: 'json',
                context: $operation->getNormalizationContext()
            ),
            status: $operation->getStatusCode(),
            json: true
        );
    }
}
