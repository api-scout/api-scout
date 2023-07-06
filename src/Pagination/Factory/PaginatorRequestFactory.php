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

namespace ApiScout\Pagination\Factory;

use ApiScout\OpenApi\Model\PaginationOptions;
use ApiScout\Operation;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class PaginatorRequestFactory implements PaginatorRequestFactoryInterface
{
    private readonly Request $request;
    private readonly Operation $operation;

    public function __construct(
        private readonly ResourceCollectionFactoryInterface $resourceCollectionFactory,
        private readonly PaginationOptions $paginationOptions,
        RequestStack $requestStack,
    ) {
        if ($requestStack->getCurrentRequest() === null) {
            throw new LogicException('Request should be first initialized.');
        }

        $this->request = $requestStack->getCurrentRequest();
        $this->operation = $this->getOperationFromRequest();
    }

    public function getCurrentPage(): int
    {
        $currentPage = $this->request->get($this->paginationOptions->getPaginationPageParameterName());

        if (!is_numeric($currentPage)) {
            return 1;
        }

        return (int) $currentPage;
    }

    public function getItemsPerPage(): int
    {
        return $this->operation->getPaginationItemsPerPage() ?? $this->paginationOptions->getPaginationItemsPerPage();
    }

    public function isPaginationEnabled(): bool
    {
        return $this->operation->isPaginationEnabled();
        //        if ($operation->isPaginationEnabled()) {
        //            return true;
        //        }
        //
        //        return $this->paginationOptions->isPaginationEnabled();
    }

    public function getOperationFromRequest(): Operation
    {
        if (!$this->request->attributes->has('_controller_class')
            && !$this->request->attributes->has('_route_name')
        ) {
            throw new LogicException('Cannot get Operation when using non declared route with api loader annotation');
        }

        return $this->resourceCollectionFactory->create()
            ->getOperation(
                /** @phpstan-ignore-next-line this value will always be a string */
                $this->request->attributes->get('_route_name')
            )
        ;
    }
}
