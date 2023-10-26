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

namespace ApiScout\Response\Serializer\Normalizer;

use ApiScout\Operation;

interface NormalizerInterface
{
    public function normalize(mixed $object, Operation $operation): array;
}
