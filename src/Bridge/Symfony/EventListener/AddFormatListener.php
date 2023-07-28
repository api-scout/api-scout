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

use ApiScout\OpenApi\Http\FormatMatcher;
use ApiScout\Resource\Factory\ResourceCollectionFactoryInterface;
use Negotiation\Negotiator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Chooses the format to use according to the Accept header and supported formats.
 *
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class AddFormatListener
{
    public function __construct(
        private readonly ResourceCollectionFactoryInterface $resourceCollectionFactory,
        private readonly Negotiator $negotiator
    ) {
    }

    /**
     * Sets the applicable format to the HttpFoundation Request.
     *
     * @throws NotFoundHttpException
     * @throws NotAcceptableHttpException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('_route_name')
            && $request->attributes->get('_route_name') === null
        ) {
            return;
        }

        $operation = $this->resourceCollectionFactory->create()
            ->getOperation(
                /** @phpstan-ignore-next-line this value will always be a string */
                $request->attributes->get('_route_name')
            )
        ;

        $formats = $operation->getOutputFormats();

        $this->addRequestFormats($request, $formats);

        // Empty strings must be converted to null because the Symfony router doesn't support parameter typing before 3.2 (_format)
        if (null === $routeFormat = $request->attributes->get('_format') ?: null) {
            $flattenedMimeTypes = $this->flattenMimeTypes($formats);
            $mimeTypes = array_keys($flattenedMimeTypes);
        } elseif (!isset($formats[$routeFormat])) {
            /* @phpstan-ignore-next-line $routeFormat is a string */
            throw new NotFoundHttpException(sprintf('Format "%s" is not supported', $routeFormat));
        } else {
            /** @phpstan-ignore-next-line $routeFormat is a string */
            $mimeTypes = Request::getMimeTypes($routeFormat);
            $flattenedMimeTypes = $this->flattenMimeTypes([$routeFormat => $mimeTypes]);
        }

        /** @var string|null $accept */
        $accept = $request->headers->get('Accept');
        if ($accept !== null) {
            if (null === $mediaType = $this->negotiator->getBest($accept, $mimeTypes)) {
                throw $this->getNotAcceptableHttpException($accept, $flattenedMimeTypes);
            }

            $formatMatcher = new FormatMatcher($formats);
            /** @phpstan-ignore-next-line */
            $request->setRequestFormat($formatMatcher->getFormat($mediaType->getType()));

            return;
        }

        // Then use the Symfony request format if available and applicable
        $requestFormat = $request->getRequestFormat('') ?: null;
        if ($requestFormat !== null) {
            $mimeType = $request->getMimeType($requestFormat);

            if ($mimeType === null) {
                return;
            }

            if (isset($flattenedMimeTypes[$mimeType])) {
                return;
            }

            throw $this->getNotAcceptableHttpException($mimeType, $flattenedMimeTypes);
        }

        // Finally, if no Accept header nor Symfony request format is set, return the default format
        foreach ($formats as $format => $mimeType) {
            $request->setRequestFormat($format);

            return;
        }
    }

    /**
     * Adds the supported formats to the request.
     *
     * This is necessary for {@see Request::getMimeType} and {@see Request::getMimeTypes} to work.
     */
    private function addRequestFormats(Request $request, array $formats): void
    {
        foreach ($formats as $format => $mimeTypes) {
            $request->setFormat($format, (array) $mimeTypes);
        }
    }

    /**
     * Retries the flattened list of MIME types.
     */
    private function flattenMimeTypes(array $formats): array
    {
        $flattenedMimeTypes = [];
        foreach ($formats as $format => $mimeTypes) {
            foreach ($mimeTypes as $mimeType) {
                $flattenedMimeTypes[$mimeType] = $format;
            }
        }

        return $flattenedMimeTypes;
    }

    /**
     * Retrieves an instance of NotAcceptableHttpException.
     */
    private function getNotAcceptableHttpException(string $accept, array $mimeTypes): NotAcceptableHttpException
    {
        return new NotAcceptableHttpException(sprintf(
            'Requested format "%s" is not supported. Supported MIME types are "%s".',
            $accept,
            implode('", "', array_keys($mimeTypes))
        ));
    }
}
