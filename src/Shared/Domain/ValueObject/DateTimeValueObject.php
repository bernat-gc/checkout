<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\ValueObject;

use DateTimeImmutable;

class DateTimeValueObject
{
    private const DEFAULT_FORMAT = DateTimeImmutable::ATOM;

    public function __construct(
        private readonly DateTimeImmutable $value
    ) {
    }

    public function __toString(): string
    {
        return $this->value->format(self::DEFAULT_FORMAT);
    }

    public function equals(DateTimeValueObject $other): bool
    {
        return $this->value === $other->value;
    }

    public static function now(): static
    {
        return new static(new DateTimeImmutable());
    }

    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return $this->value;
    }
}
