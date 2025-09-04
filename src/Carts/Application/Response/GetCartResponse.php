<?php

namespace BGC\Checkout\Carts\Application\Response;

use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Shared\Application\Handler\Query\ResponseInterface;

class GetCartResponse implements ResponseInterface
{
    public function __construct(
        private readonly Cart $cart
    ) {
    }

    public function cart(): Cart
    {
        return $this->cart;
    }
}
