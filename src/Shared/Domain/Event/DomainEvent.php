<?php

namespace Siroko\Checkout\Shared\Domain\Event;

use DateTimeImmutable;

abstract class DomainEvent
{
    abstract public function eventName(): string;

    public function __construct(
        private readonly string $aggregateId,
        private readonly array $data,
        private readonly DateTimeImmutable $occurredOn = new DateTimeImmutable()
    ) {}
}
