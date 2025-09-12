<?php

/**
 * Abstract class for aggregate root entities
 */

namespace BGC\Checkout\Shared\Domain;

use BGC\Checkout\Shared\Domain\Event\DomainEvent;
use BGC\Checkout\Shared\Domain\ValueObject\DateTimeValueObject;

abstract class AggregateRoot
{   
    private $events = [];

    protected DateTimeValueObject $createdAt;
    protected DateTimeValueObject $updatedAt;

    public function __construct()
    {
        $this->refreshTimestamps();
    }

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

    public function refreshTimestamps(): void
    {
        $now = DateTimeValueObject::now();

        if (!isset($this->createdAt)) {
            $this->createdAt = clone $now;
        }

        $this->updatedAt = $now;
    }

    public function createdAt(): DateTimeValueObject
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeValueObject
    {
        return $this->updatedAt;
    }
}
