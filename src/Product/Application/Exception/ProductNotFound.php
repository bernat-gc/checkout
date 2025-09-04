<?php

namespace BGC\Checkout\Product\Application\Exception;

use Exception;

class ProductNotFound extends Exception
{
    public function __construct(string $productId)
    {
        parent::__construct("Product with id {$productId} not found.");
    }
}
