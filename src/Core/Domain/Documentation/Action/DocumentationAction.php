<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/*
 * This file is part of the ApiScout project.
 *
 * Copyright (c) 2023 ApiScout
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiScout\Core\Domain\Documentation\Action;

use ApiScout\Core\Domain\Documentation\Documentation;
use ApiScout\Core\Domain\Documentation\DocumentationInterface;
use ApiScout\Core\Domain\OpenApi\Factory\OpenApiFactoryInterface;
use ApiScout\Core\Domain\OpenApi\OpenApi;
use ApiScout\Core\Domain\Resource\Factory\ResourceCollectionFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Generates the API documentation.
 *
 * @author Marvin Courcier <marvin.courcier@gmail.com>
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
