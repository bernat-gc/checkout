<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\ValueObject;

use BGC\Checkout\Shared\Domain\Exception\InvalidNegativeInteger;

class NonNegativeIntegerValueObject extends IntegerValueObject
{
    public function __construct(int $value)
    {
        parent::__construct($value);
        $this->ensureNonNegative();
    }

    protected function ensureNonNegative(): void
    {
        if ($this->value < 0) {
            throw new InvalidNegativeInteger();
        }
    }
}
