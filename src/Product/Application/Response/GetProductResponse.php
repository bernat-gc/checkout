<?php

namespace BGC\Checkout\Product\Application\Response;

use BGC\Checkout\Product\Domain\Product;
use BGC\Checkout\Shared\Application\Handler\Query\ResponseInterface;

class GetProductResponse implements ResponseInterface
{
    public function __construct(
        private readonly ?Product $product
    ) {
    }

    public function product(): ?Product
    {
        return $this->product;
    }
}
