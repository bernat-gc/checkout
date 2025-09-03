<?php

namespace BGC\Checkout\Carts\Domain\Event;

use BGC\Checkout\Shared\Domain\Event\DomainEvent;

class CartCreated extends DomainEvent
{
    public function eventName(): string
    {
        return 'siroko.checkout.1.cart.created';
    }
}
