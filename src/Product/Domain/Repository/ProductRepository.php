<?php

namespace BGC\Checkout\Product\Domain\Repository;

use BGC\Checkout\Product\Domain\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

interface ProductRepository
{
    public function findById(Uuid $productId): ?Product;
}
