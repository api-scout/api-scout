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

namespace ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Pagination\GetDoctrinePaginatedCollectionDummy;

use ApiScout\Attribute\GetCollection;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\Dummy;
use ApiScout\Tests\Fixtures\TestBundle\Controller\Dummy\DummyQueryInput;
use ApiScout\Tests\Fixtures\TestBundle\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

/**
 * @author Marvin Courcier <marvincourcier.dev@gmail.com>
 */
final class GetDoctrinePaginatedCollectionDummyController
{
    #[GetCollection(
        '/doctrine_paginated_dummies',
        name: 'app_get_dummy_doctrine_paginated_collection',
        resource: Dummy::class,
    )]
    public function __invoke(
        #[MapQueryString] DummyQueryInput $query,
        EntityManagerInterface $entityManager,
    ): Paginator {
        $bookRepository = $entityManager
            ->createQueryBuilder()
            ->select('book')
            ->from(Book::class, 'book')
            ->setMaxResults($query->getItemsPerPage())
            ->getQuery()
        ;

        return new Paginator($bookRepository);
    }
}
