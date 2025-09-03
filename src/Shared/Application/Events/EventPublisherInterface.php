<?php

namespace BGC\Checkout\Shared\Application\Events;

use BGC\Checkout\Shared\Domain\Event\DomainEvent;

interface EventPublisherInterface
{
    public function publish(DomainEvent ...$event);
}
