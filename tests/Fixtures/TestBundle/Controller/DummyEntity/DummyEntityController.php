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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\DummyEntity;

use ApiScout\Attribute\Post;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class DummyEntityController
{
    #[Post(
        '/api/dummies_entity',
        name: 'app_add_dummy_entity',
        resource: DummyEntity::class,
        normalizationContext: ['groups' => 'read']
    )]
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => 'write',
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
            ]
        )] DummyEntity $dummyEntityInput,
    ): DummyEntity {
        return new DummyEntity(
            1,
            $dummyEntityInput->firstName,
            $dummyEntityInput->lastName,
            new DummyCompanyEntity(
                1,
                $dummyEntityInput->addressEntity->name ?? '',
                $dummyEntityInput->addressEntity->description ?? '',
            )
        );
    }
}
