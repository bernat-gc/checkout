<?php

namespace BGC\Checkout\Carts\Domain\Repository;

use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

interface CartRepository
{
    public function findById(Uuid $cartId): ?Cart;

    public function save(Cart $cart): void;
}
