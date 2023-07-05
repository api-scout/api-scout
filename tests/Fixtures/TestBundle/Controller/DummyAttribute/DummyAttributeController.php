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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\DummyAttribute;

use ApiScout\Core\Domain\Attribute\ApiProperty;
use ApiScout\Core\Domain\Attribute\Delete;
use ApiScout\Core\Domain\Attribute\Get;
use ApiScout\Core\Domain\Attribute\GetCollection;
use ApiScout\Core\Domain\Attribute\Patch;
use ApiScout\Core\Domain\Attribute\Post;
use ApiScout\Core\Domain\Attribute\Put;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class DummyAttributeController extends AbstractController
{
    #[GetCollection(
        path: '/dummies_attribute',
        name: 'app_get_dummy_attribute_collection',
        output: DummyAttributeCollectionOutput::class,
        class: DummyAttribute::class,
        filters: [
            new ApiProperty(name: 'name', type: 'string', required: false, description: 'The name of the champion'),
            new ApiProperty(name: 'page', type: 'integer', required: true, description: 'The page my mate'),
        ]
    )]
    public function getDummyAttributeCollection(
    ): Response {
        return new Response();
    }

    #[Get(
        path: '/dummies_attribute/{id}',
        name: 'app_get_dummy_attribute',
        output: DummyAttributeOutput::class,
        class: DummyAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'string'),
        ]
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
        class: DummyAttribute::class
    )]
    public function postDummyAttribute(): Response
    {
        return new Response(status: Response::HTTP_CREATED);
    }

    #[Patch(
        path: '/dummies_attribute',
        name: 'app_update_patch_dummy_attribute',
        input: DummyAttributePayloadInput::class,
        output: DummyAttributeOutput::class,
        class: DummyAttribute::class
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
        class: DummyAttribute::class
    )]
    public function putDummyAttribute(
    ): Response {
        return new Response(status: Response::HTTP_OK);
    }

    #[Delete(
        path: '/dummies_attribute/{id}',
        name: 'app_delete_dummy_attribute',
        class: DummyAttribute::class,
        uriVariables: [
            new ApiProperty('id', 'int'),
        ]
    )]
    public function deleteDummyAttribute(
    ): Response {
        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
