<?php

namespace BGC\Checkout\Carts\Application\Command;

use BGC\Checkout\Shared\Application\Handler\Command\CommandInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class ModifyCartItemCommand implements CommandInterface
{
    private readonly Uuid $cartId;
    private readonly Uuid $cartItemId;
    private readonly int $quantity;

    public function __construct(
        string $cartId,
        string $cartItemId,
        int $quantity
    ) {
        $this->cartId = new Uuid($cartId);
        $this->cartItemId = new Uuid($cartItemId);
        $this->quantity = $quantity;
    }

    public function cartId(): Uuid
    {
        return $this->cartId;
    }

    public function cartItemId(): Uuid
    {
        return $this->cartItemId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
