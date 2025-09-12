<?php

namespace BGC\Checkout\Carts\Domain\Repository;

use BGC\Checkout\Carts\Domain\Entity\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

interface CartProductRepository
{
    public function findById(Uuid $productId): ?Product;
}
