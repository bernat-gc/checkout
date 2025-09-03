<?php

namespace BGC\Checkout\Shared\Domain\Exception;

use Exception;

class ItemNotFoundInCollection extends Exception
{
    public function __construct()
    {
        parent::__construct("Item not found in collection");
    }
}
