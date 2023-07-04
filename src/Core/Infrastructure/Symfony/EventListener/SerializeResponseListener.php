<?php

declare(strict_types=1);

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiScout\Core\Infrastructure\Symfony\EventListener;

use ApiScout\Core\Domain\Attribute\CollectionOperationInterface;
use ApiScout\Core\Domain\Pagination\Factory\PaginatorRequestFactoryInterface;
use ApiScout\Core\Domain\Pagination\Paginator;
use ApiScout\Core\Domain\Resource\Factory\ResourceFactoryInterface;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SerializeResponseListener
{
    public function __construct(
        private readonly ResourceFactoryInterface $resourceFactory,
        private readonly PaginatorRequestFactoryInterface $paginatorRequestFactory,
        private readonly NormalizerInterface $apiNormalizer
    ) {
    }

    /**
     * Serializes the data to the requested format.
     */
    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if (!$request->attributes->has('_controller_class')
            && !$request->attributes->has('_route_name')
        ) {
            return;
        }

        $operation = $this->resourceFactory->initializeOperation(
            $request->attributes->get('_controller_class'), /** @phpstan-ignore-line this value will always be a string */
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
                data: ['data' => $this->apiNormalizer->normalize($controllerResult)],
                status: $operation->getStatusCode()
            ),
        );
    }
}
