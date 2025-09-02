<?php

namespace Siroko\Checkout\Carts\Domain\Exception;

use Exception;

class NonPositiveCartItemQuantity extends Exception
{
    public function __construct($quantity)
    {
        parnet::__construct("Quantity in cart items must be a positve integer");
    }
}
