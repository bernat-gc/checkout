<?php

namespace BGC\Checkout\Shared\Infrastructure;

use BGC\Checkout\Shared\Application\Events\EventPublisherInterface;
use BGC\Checkout\Shared\Domain\Event\DomainEvent;

class EventPublisher implements EventPublisherInterface
{
    public function publish(DomainEvent ...$event)
    {
    }
}
