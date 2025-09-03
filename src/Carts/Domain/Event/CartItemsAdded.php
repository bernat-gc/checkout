<?php

namespace BGC\Checkout\Carts\Domain\Event;

use BGC\Checkout\Shared\Domain\Event\DomainEvent;

class CartItemsAdded extends DomainEvent
{
    public function eventName(): string
    {
        return 'siroko.checkout.1.cart.items_added';
    }
}
