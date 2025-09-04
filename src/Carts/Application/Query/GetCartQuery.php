<?php

namespace BGC\Checkout\Carts\Application\Query;

use BGC\Checkout\Shared\Application\Handler\Query\QueryInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class GetCartQuery implements QueryInterface
{
    private readonly Uuid $cartId;

    public function __construct(
        string $cartId
    ) {
        $this->cartId = new Uuid($cartId);
    }

    public function cartId(): Uuid
    {
        return $this->cartId;
    }
}
