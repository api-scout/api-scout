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

namespace ApiScout\Documentation\Action;

use ApiScout\Documentation\Documentation;
use ApiScout\Documentation\DocumentationInterface;
use ApiScout\OpenApi\Factory\OpenApiFactoryInterface;
use ApiScout\OpenApi\OpenApi;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Generates the API documentation.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class DocumentationAction
{
    public function __construct(
        private readonly ResourceCollectionFactoryInterface $resourceCollectionFactory,
        private readonly ?OpenApiFactoryInterface $openApiFactory = null,
        private readonly string $title = '',
        private readonly string $description = '',
        private readonly string $version = '',
    ) {
    }

    public function __invoke(?Request $request = null): DocumentationInterface|OpenApi
    {
        if ($request !== null) {
            $context = ['base_url' => $request->getBaseUrl()];

            if ($request->getRequestFormat() === 'json' && $this->openApiFactory !== null) {
                return $this->openApiFactory->__invoke($context);
            }
        }

        return new Documentation(
            $this->resourceCollectionFactory->create(),
            $this->title,
            $this->description,
            $this->version
        );
    }
}
