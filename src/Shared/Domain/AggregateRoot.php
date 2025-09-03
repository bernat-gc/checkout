<?php

/**
 * Abstract class for aggregate root entities
 */

namespace BGC\Checkout\Shared\Domain;

use BGC\Checkout\Shared\Domain\Event\DomainEvent;

abstract class AggregateRoot
{   
    private $events = [];

    protected function record(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function pullDomainEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
