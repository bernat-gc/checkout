<?php

namespace Siroko\Checkout\Carts\Domain\Exception;

use Exception;

class InvalidPriceAmount extends Exception
{
    public function __construct()
    {
        parent::__construct("Amount must has at most 2 decimal digits");
    }
}
