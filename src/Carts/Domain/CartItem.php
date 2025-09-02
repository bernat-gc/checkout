<?php

/**
 * Cart item class
 */

namespace Siroko\Checkout\Carts\Domain;

use Exception;
use Siroko\Checkout\Carts\Domain\Exception\NonPositiveCartItemQuantity;
use Siroko\Checkout\Carts\Domain\ValueObject\Price;
use Siroko\Checkout\Carts\Domain\ValueObject\Product;
use Siroko\Checkout\Shared\Domain\ValueObject\Uuid;

class CartItem
{
    public function __construct(
        private Uuid $id,
        private Product $product,
        private int $quantity
    ) {
        $this->ensureQuantityIsPositive();
    }

    public function getUnitPrice(): Price
    {
        return $this->product->price();
    }

    public function getTotalPrice(): Price
    {
        return $this->getUnitPrice()->multiply($this->quantity);
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function increase(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function decrease(int $quantity): void
    {
        $this->quantity -= $quantity;
        $this->ensureQuantityIsPositive();
    }

    private function ensureQuantityIsPositive()
    {
        if ($this->quantity <= 0) {
            throw new NonPositiveCartItemQuantity();
        }
    }

    public function toArray(): array
    {
        return [
            'id' => (string)$this->id,
            'product' => $this->product->toArray(),
            'quantity' => $this->quantity
        ];
    }
}
