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

use ApiScout\OpenApi\Http\AbstractResponse;
use ApiScout\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * OpenApi Json response.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class OpenApiLoaderResponseListener
{
    public function __construct(
        private readonly NormalizerInterface $apiNormalizer
    ) {
    }

    /**
     * Serializes the data to the requested format.
     */
    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();

        if (!$controllerResult instanceof OpenApi) {
            return;
        }

        $event->setResponse(
            new JsonResponse(
                data: $this->apiNormalizer->normalize($controllerResult),
                status: AbstractResponse::HTTP_OK
            ),
        );
    }
}
