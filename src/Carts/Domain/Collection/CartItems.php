<?php

namespace Siroko\Checkout\Carts\Domain\Collection;

use Siroko\Checkout\Carts\Domain\CartItem;
use Siroko\Checkout\Carts\Domain\ValueObject\Price;
use Siroko\Checkout\Carts\Domain\ValueObject\Product;
use Siroko\Checkout\Shared\Domain\Collection\Collection;

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
            $this->items
        );

        return Price::sum(...$prices);
    }

    public function findByProduct(Product $product): ?CartItem
    {
        $itemsWithProduct = array_filter(
            $this->items,
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
            $this->items,
            fn ($item) => $item->id()->equals($itemId)
        );

        if (!$itemsWithId) {
            return null;
        }

        return $itemsWithId[0];
    }

    public function addItem(CartItem $item): void
    {
        $cartItem = $this->findByProduct($item->product());

        if (!$cartItem) {
            $this->add($item);
        } else {
            $cartItem->increase($item->quantity);
        }
    }



    public function toArray(): array
    {
        return array_map(
            fn ($item) => $item->toArray(),
            $this->items
        );
    }
}
