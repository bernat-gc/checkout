<?php

namespace BGC\Checkout\Carts\Application\Command;

use BGC\Checkout\Shared\Application\Handler\Command\CommandInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Quantity;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class AddItemCommand implements CommandInterface
{
    private readonly Uuid $id;
    private readonly Uuid $cartId;
    private readonly Uuid $productId;
    private readonly Quantity $quantity;

    public function __construct(
        string $id,
        string $cartId,
        string $productId,
        int $quantity,
    ) {
        $this->id = new Uuid($id);
        $this->cartId = new Uuid($cartId);
        $this->productId = new Uuid($productId);
        $this->quantity = new Quantity($quantity);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function cartId(): Uuid
    {
        return $this->cartId;
    }

    public function productId(): Uuid
    {
        return $this->productId;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }
}
