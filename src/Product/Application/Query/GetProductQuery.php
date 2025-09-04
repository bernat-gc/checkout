<?php

namespace BGC\Checkout\Product\Application\Query;

use BGC\Checkout\Shared\Application\Handler\Query\QueryInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class GetProductQuery implements QueryInterface
{
    private readonly Uuid $productId;

    public function __construct(
        string $productId
    ) {
        $this->productId = new Uuid($productId);
    }

    public function productId(): Uuid
    {
        return $this->productId;
    }
}
