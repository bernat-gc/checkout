<?php

/**
 * Value Object for uuid
 */

namespace BGC\Checkout\Shared\Domain\ValueObject;

use BGC\Checkout\Shared\Domain\Exception\InvalidUuidException;

class Uuid
{
    protected const PATTERN = <<<REGEX
        /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/
    REGEX;

    public function __construct(
        private readonly string $value
    ) {
        $this->ensureValidUuid($value);
    }

    protected function ensureValidUuid(string $value): void
    {
        if (preg_match(self::PATTERN, $value) != 1) {
            throw new InvalidUuidException($value);
        }
    }

    public function equals(Uuid $other): bool
    {
        return $this->value == $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
