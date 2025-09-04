<?php

namespace BGC\Checkout\Carts\Application\Command;

use BGC\Checkout\Shared\Application\Handler\Command\CommandInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class DeleteCartItemCommand implements CommandInterface
{
    private readonly Uuid $cartId;
    private readonly Uuid $cartItemId;

    public function __construct(
        string $cartId,
        string $cartItemId
    ) {
        $this->cartId = new Uuid($cartId);
        $this->cartItemId = new Uuid($cartItemId);
    }

    public function cartId(): Uuid
    {
        return $this->cartId;
    }

    public function cartItemId(): Uuid
    {
        return $this->cartItemId;
    }
}
