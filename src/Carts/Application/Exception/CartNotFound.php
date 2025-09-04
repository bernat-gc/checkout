<?php

namespace BGC\Checkout\Carts\Application\Exception;

use Exception;

class CartNotFound extends Exception
{
    public function __construct(string $cartId)
    {
        parent::__construct("Cart with id {$cartId} not found.");
    }
}
