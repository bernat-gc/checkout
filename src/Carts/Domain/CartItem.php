<?php

/**
 * Cart item class
 */

namespace BGC\Checkout\Carts\Domain;

use Exception;
use BGC\Checkout\Carts\Domain\Exception\NonPositiveCartItemQuantity;
use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Carts\Domain\ValueObject\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class CartItem
{
    private ?Cart $cart;

    public function __construct(
        private Uuid $id,
        private Product $product,
        private int $quantity
    ) {
        $this->ensureQuantityIsPositive();
    }

    public function setCart(?Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getUnitPrice(): Price
    {
        return $this->product->price();
    }

    public function getTotalPrice(): Price
    {
        return $this->getUnitPrice()->multiply($this->quantity);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function modifyQuantity(int $newQuantity): void
    {
        $this->ensureQuantityIsPositive($newQuantity);
        $this->quantity = $newQuantity;
    }

    public function increase(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function decrease(int $quantity): void
    {
        $this->ensureQuantityIsPositive($this->quantity - $quantity);
        $this->quantity -= $quantity;
    }

    private function ensureQuantityIsPositive(?int $quantity = null): void
    {
        if (!$quantity) {
            $quantity = $this->quantity;
        }

        if ($quantity <= 0) {
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
