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

namespace ApiScout\Response\Pagination;

use ApiScout\Operation;
use LogicException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * he ApiScout Pagination Metadata.
 *
 * @author Jérémy Romey <jeremy@free-agent.fr>
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class PaginationMetadata implements PaginationMetadataInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack
    ) {
    }

    public function getMetadata(
        PaginationInterface $pagination,
        Operation $operation
    ): array {
        $metadata = $pagination->getMetadata();
        /**
         * @var string $routeName at this point the name Operation should never be null
         */
        $routeName = $operation->getName();

        if ($pagination->getCurrentPage() > 1) {
            $metadata['prev'] = $this->generateUrl($routeName, $pagination->getCurrentPage() - 1);
        }

        if ($this->shouldGenerateNextPageUrl($pagination)) {
            $metadata['next'] = $this->generateUrl($routeName, $pagination->getCurrentPage() + 1);
        }

        return $metadata;
    }

    private function generateUrl(string $routeName, int $page): string
    {
        $request = $this->requestStack->getMainRequest();

        if ($request === null) {
            throw new LogicException('Could not generate Url because the request is null');
        }

        

        return $this->urlGenerator->generate(
            $routeName,
            array_merge(
                $request->query->all(),
                [
                    'page' => $page,
                ]
            ),
        );
    }

    private function shouldGenerateNextPageUrl(PaginationInterface $pagination): bool
    {
        if ($pagination->getTotalItems() === null) {
            return true;
        }

        return $pagination->getCurrentPage() * $pagination->getItemsPerPage() < $pagination->getTotalItems();
    }
}
