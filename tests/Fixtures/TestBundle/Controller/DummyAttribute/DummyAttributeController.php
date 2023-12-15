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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\DummyAttribute;

use ApiScout\Attribute\ApiProperty;
use ApiScout\Attribute\Delete;
use ApiScout\Attribute\Get;
use ApiScout\Attribute\GetCollection;
use ApiScout\Attribute\Patch;
use ApiScout\Attribute\Post;
use ApiScout\Attribute\Put;
use ApiScout\OpenApi\Model;
use ApiScout\Response\Pagination\Pagination;
use ArrayObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class DummyAttributeController extends AbstractController
{
    #[GetCollection(
        path: '/dummies_attribute',
        name: 'app_get_dummy_attribute_collection',
        output: Pagination::class,
        resource: DummyAttribute::class,
        filters: [
            new ApiProperty(name: 'name', type: 'string', required: false, description: 'The name of the champion'),
            new ApiProperty(name: 'page', type: 'integer', required: true, description: 'The page my mate'),
        ],
    )]
    public function getDummyAttributeCollection(): Response
    {
        return new Response();
    }

    #[Get(
        path: '/dummies_attribute/{id}',
        name: 'app_get_dummy_attribute',
        output: DummyAttributeOutput::class,
        resource: DummyAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'string'),
        ],
    )]
    public function getDummyAttribute(): Response
    {
        return new Response();
    }

    #[Post(
        path: '/dummies_attribute',
        name: 'app_post_dummy_attribute',
        input: DummyAttributePayloadInput::class,
        output: DummyAttributeOutput::class,
        resource: DummyAttribute::class,
    )]
    public function postDummyAttribute(): Response
    {
        return new Response(status: Response::HTTP_CREATED);
    }

    #[Post(
        path: '/upload_file_dummies_attribute',
        name: 'app_upload_file_dummy_attribute',
        resource: DummyAttribute::class,
        openapi: new Model\Operation(
            requestBody: new Model\RequestBody(
                content: new ArrayObject([
                    'multipart/form-data' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'file' => [
                                    'type' => 'string',
                                    'format' => 'binary',
                                ],
                            ],
                        ],
                    ],
                ]),
            ),
        ),
    )]
    public function uploadFileDummyAttribute(): Response
    {
        return new Response(status: Response::HTTP_CREATED);
    }

    #[Patch(
        path: '/dummies_attribute',
        name: 'app_update_patch_dummy_attribute',
        input: DummyAttributePayloadInput::class,
        output: DummyAttributeOutput::class,
        resource: DummyAttribute::class,
    )]
    public function patchDummyAttribute(
    ): Response {
        return new Response(status: Response::HTTP_OK);
    }

    #[Put(
        path: '/dummies_attribute',
        name: 'app_update_put_dummy_attribute',
        input: DummyAttributePayloadInput::class,
        output: DummyAttributeOutput::class,
        resource: DummyAttribute::class,
    )]
    public function putDummyAttribute(
    ): Response {
        return new Response(status: Response::HTTP_OK);
    }

    #[Delete(
        path: '/dummies_attribute/{id}',
        resource: DummyAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'int'),
        ],
    )]
    public function deleteDummyAttribute(
    ): Response {
        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
