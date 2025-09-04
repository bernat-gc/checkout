<?php

namespace BGC\Checkout\Carts\Application\Command;

use BGC\Checkout\Shared\Application\Handler\Command\CommandInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class AddItemCommand implements CommandInterface
{
    private readonly Uuid $id;
    private readonly Uuid $cartId;
    private readonly Uuid $productId;
    private readonly string $description;
    private readonly int $quantity;
    private readonly int $centsAmount;
    private readonly string $currency;

    public function __construct(
        string $id,
        string $cartId,
        string $productId,
        string $description,
        int $quantity,
        int $centsAmount,
        string $currency
    ) {
        $this->id = new Uuid($id);
        $this->cartId = new Uuid($cartId);
        $this->productId = new Uuid($productId);
        $this->description = $description;
        $this->quantity = $quantity;
        $this->centsAmount = $centsAmount;
        $this->currency = $currency;
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

    public function description(): string
    {
        return $this->description;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function centsAmount(): int
    {
        return $this->centsAmount;
    }
}
