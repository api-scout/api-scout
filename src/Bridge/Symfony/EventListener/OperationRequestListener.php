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

use ApiScout\Operation;
use ApiScout\Resource\OperationProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use function is_string;

/**
 * Add the proper Operation to the request for further handling.
 *
 * @author Jules Pietri <jules@heahprod.com>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class OperationRequestListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly OperationProviderInterface $resourceCollectionFactory,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['setOperation', 30],
        ];
    }

    public function setOperation(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $controller = $request->attributes->get('_controller');

        if (!is_string($controller)) {
            return;
        }

        $operation = $this->resourceCollectionFactory->get($controller);

        if ($operation instanceof Operation) {
            $request->attributes->set('_api_scout_operation', $operation);
        }
    }
}
