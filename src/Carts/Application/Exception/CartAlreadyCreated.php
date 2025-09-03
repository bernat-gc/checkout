<?php

namespace BGC\Checkout\Carts\Application\Exception;

use Exception;

class CartAlreadyCreated extends Exception
{
    public function __construct()
    {
        parent::__construct("Cart already created.");
    }
}
