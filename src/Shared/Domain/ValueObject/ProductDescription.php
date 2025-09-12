<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\ValueObject;

use BGC\Checkout\Shared\Domain\ValueObject\StringValueObject;

class ProductDescription extends StringValueObject
{
    private const MAX_LENGTH = 50;

    public function maxLength(): ?int
    {
        return self::MAX_LENGTH;
    }
}
