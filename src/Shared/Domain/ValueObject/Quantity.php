<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\ValueObject;

use BGC\Checkout\Carts\Domain\Exception\NonPositiveCartItemQuantity;
use BGC\Checkout\Shared\Domain\Exception\InvalidNegativeInteger;
use BGC\Checkout\Shared\Domain\ValueObject\NonNegativeIntegerValueObject;

class Quantity extends NonNegativeIntegerValueObject
{
    public function __construct(int $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidNegativeInteger $exception) {
            throw new NonPositiveCartItemQuantity();
        }
    }

    public function decrease(int $decrement): static
    {
        try {
            parent::increase($decrement);
        } catch (InvalidNegativeInteger $exception) {
            throw new NonPositiveCartItemQuantity();
        }
    }
}
