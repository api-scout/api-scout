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
use ApiScout\Pagination\PaginationInterface;
use ApiScout\Pagination\PaginationProviderInterface;
use ApiScout\ResponseGeneratorInterface;
use ApiScout\Serializer\ResponseSerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        private readonly ResponseGeneratorInterface $prepareResponse,
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

        $data = $event->getControllerResult();

        if (!is_iterable($data) && !is_object($data) && !$data instanceof PaginationInterface) {
            return;
        }

        if ($operation instanceof CollectionOperationInterface && $operation->isPaginationEnabled()) {
            $data = $this->paginationProvider->provide($data, $operation);
        }

        $event->setResponse(
            new JsonResponse(
                data: $this->responseSerializer->serialize(
                    data: $this->prepareResponse->generate($data, $operation),
                    context: $operation->getNormalizationContext()
                ),
                status: $operation->getStatusCode(),
                json: true
            ),
        );
    }
}
