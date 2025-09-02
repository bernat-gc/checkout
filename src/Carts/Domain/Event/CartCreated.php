<?php

namespace Siroko\Checkout\Carts\Domain\Event;

use Siroko\Checkout\Shared\Domain\Event\DomainEvent;

class CartCreated extends DomainEvent
{
    public function eventName(): string
    {
        return 'siroko.checkout.1.cart.created';
    }
}
