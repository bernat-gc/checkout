<?php

namespace BGC\Checkout\Carts\Application\Command;

use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class CreateCartCommand
{
    private readonly Uuid $cartId;
    private readonly Uuid $userId;

    public function __construct(
        string $cartId,
        string $userId
    ) {
        $this->cartId = new Uuid($cartId);
        $this->userId = new Uuid($userId);
    }

    public function userId(): Uuid
    {
        return $this->userId;
    }

    public function cartId(): Uuid
    {
        return $this->cartId;
    }
}
