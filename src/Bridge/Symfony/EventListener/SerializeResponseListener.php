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
use ApiScout\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Pagination\Paginator;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SerializeResponseListener
{
    public function __construct(
        private readonly ResourceCollectionFactoryInterface $resourceCollectionFactory,
        private readonly PaginatorRequestFactoryInterface $paginatorRequestFactory,
        private readonly NormalizerInterface $apiNormalizer,
        private readonly string $responseItemKey
    ) {
    }

    /**
     * Serializes the data to the requested format.
     */
    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if (!$request->attributes->has('_route_name')) {
            return;
        }

        $operation = $this->resourceCollectionFactory->create()->getOperation(
            $request->attributes->get('_route_name') /** @phpstan-ignore-line this value will always be a string */
        );

        if ($operation instanceof CollectionOperationInterface
            && $this->paginatorRequestFactory->isPaginationEnabled()
            && !$controllerResult instanceof Paginator
        ) {
            if (!is_iterable($controllerResult)) {
                throw new LogicException('Controller response from Collection Operation should be iterable.');
            }

            $paginator = new Paginator(
                $controllerResult,
                $this->paginatorRequestFactory->getCurrentPage(),
                $this->paginatorRequestFactory->getItemsPerPage()
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
            && $this->paginatorRequestFactory->isPaginationEnabled()
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
                data: [$this->responseItemKey => $this->apiNormalizer->normalize($controllerResult)],
                status: $operation->getStatusCode()
            ),
        );
    }
}
