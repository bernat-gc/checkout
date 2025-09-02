<?php

/**
 * Abstract class for aggregate root entities
 */

namespace Siroko\Checkout\Shared\Domain;

use Siroko\Checkout\Shared\Domain\Event\DomainEvent;

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
