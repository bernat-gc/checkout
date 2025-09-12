<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\ValueObject;

use BGC\Checkout\Shared\Domain\Exception\StringTooLong;

abstract class StringValueObject
{
    public abstract function maxLength(): ?int;

    public function __construct(
        protected readonly string $value
    ) {
        $this->validateLength($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(StringValueObject $other): bool
    {
        return $this->value === $other->value;
    }

    public function sameAs(string $value): bool
    {
        return $this->value === $value;
    }

    public function validateLength(string $value): void
    {
        $maxLength = $this->maxLength();

        if (is_int($maxLength) && strlen($value) > $maxLength) {
            throw new StringTooLong(strlen($value), $maxLength);
        }
    }
}
