<?php

namespace BGC\Checkout\Carts\Domain\Collection;

use BGC\Checkout\Carts\Domain\CartItem;
use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Carts\Domain\ValueObject\Product;
use BGC\Checkout\Shared\Domain\Collection\Collection;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class CartItems extends Collection
{
    protected function itemClass(): string
    {
        return CartItem::class;
    }

    public function getTotalPrice(): Price
    {
        $prices = array_map(
            fn ($item) => $item->getTotalPrice(),
            $this->elements()
        );

        return Price::sum(...$prices);
    }

    public function findByProduct(Product $product): ?CartItem
    {
        $itemsWithProduct = array_filter(
            $this->elements(),
            fn ($item) => $item->product()->equals($product)
        );

        if (!$itemsWithProduct) {
            return null;
        }

        return $itemsWithProduct[0];
    }

    public function findById(Uuid $itemId): ?CartItem
    {
        $itemsWithId = array_filter(
            $this->elements(),
            fn ($item) => $item->id()->equals($itemId)
        );
        if (!$itemsWithId) {
            return null;
        }

        return reset($itemsWithId);
    }

    public function addItem(CartItem $item): void
    {
        $cartItem = $this->findByProduct($item->product());

        if (!$cartItem) {
            $this->add($item);
        } else {
            $cartItem->increase($item->quantity());
        }
    }

    public function toArray(): array
    {
        return array_map(
            fn ($item) => $item->toArray(),
            $this->elements()
        );
    }
}
