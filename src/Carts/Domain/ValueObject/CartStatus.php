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

    public static function fromString(string $value): static
    {
        return match($value) {
            'Shopping' => self::Shopping,
            'Ordered' => self::Ordered
        };
    }

    public static function options(): array
    {
        return array_map(fn($item) => $item->value, self::cases());
    }
}
