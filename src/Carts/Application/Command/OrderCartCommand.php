<?php

namespace BGC\Checkout\Carts\Application\Command;

use BGC\Checkout\Shared\Application\Handler\Command\CommandInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class OrderCartCommand implements CommandInterface
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
