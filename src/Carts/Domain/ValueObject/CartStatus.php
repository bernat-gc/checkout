<?php

namespace BGC\Checkout\Carts\Domain\ValueObject;

enum CartStatus: string
{
    case Shopping   = 'Shopping';
    case Ordered    = 'Ordered';

    public function allowModification(): bool
    {
        return match($this) {
            self::Shopping => true,
            self::Ordered => false,
        };
    }
}
