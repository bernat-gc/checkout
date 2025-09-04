<?php

namespace BGC\Checkout\Carts\Application\Exception;

use Exception;

class CartItemNotFound extends Exception
{
    public function __construct(string $itemId)
    {
        parent::__construct("Cart item with id {$itemId} not found.");
    }
}
