<?php

/**
 * Cart item class
 */

namespace BGC\Checkout\Carts\Domain;

use Exception;
use BGC\Checkout\Carts\Domain\Exception\NonPositiveCartItemQuantity;
use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Carts\Domain\Entity\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Quantity;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class CartItem
{
    private ?Cart $cart;

    public function __construct(
        private Uuid $id,
        private Product $product,
        private Quantity $quantity
    ) {
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

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function modifyQuantity(Quantity $newQuantity): void
    {
        $this->quantity = $newQuantity;
    }

    public function increase(int $increment): void
    {
        $this->quantity = $this->quantity->increase($increment);
    }

    public function decrease(int $decrement): void
    {
        $this->quantity = $this->quantity->decrease($decrement);
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
