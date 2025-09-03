<?php

/**
 * Abstract class for aggregate root entities
 */

namespace BGC\Checkout\Shared\Domain;

use BGC\Checkout\Shared\Domain\Event\DomainEvent;
use DateTimeImmutable;

abstract class AggregateRoot
{   
    private $events = [];

    protected DateTimeImmutable $createdAt;
    protected DateTimeImmutable $updatedAt;

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
        $now = new DateTimeImmutable();

        if (!isset($this->createdAt)) {
            $this->createdAt = clone $now;
        }

        $this->updatedAt = $now;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
