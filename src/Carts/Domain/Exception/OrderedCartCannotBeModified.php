<?php

namespace BGC\Checkout\Carts\Domain\Exception;

use Exception;

class OrderedCartCannotBeModified extends Exception
{
    public function __construct()
    {
        parent::__construct('Carts that have been already ordered can not be modified.');
    }
}
