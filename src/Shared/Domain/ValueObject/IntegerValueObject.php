<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\ValueObject;

class IntegerValueObject
{
    public function __construct(
        protected int $value
    ) {
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function equals(IntegerValueObject $other): bool
    {
        return $this->value === $other->value;
    }

    public function gt(IntegerValueObject $other): bool
    {
        return $this->value > $other->value;
    }

    public function gte(IntegerValueObject $other): bool
    {
        return $this->value >= $other->value;
    }

    public function lt(IntegerValueObject $other): bool
    {
        return $this->value < $other->value;
    }

    public function lte(IntegerValueObject $other): bool
    {
        return $this->value <= $other->value;
    }

    public function increase(int $increment): static
    {
        return new static($this->value + $increment);
    }

    public function decrease(int $decrement): static
    {
        return new static($this->value - $decrement);
    }
}
