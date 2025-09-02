<?php

/**
 * Product class
 */

namespace Siroko\Checkout\Carts\Domain\ValueObject;

use Siroko\Checkout\Carts\Domain\ValueObject\Price;
use Siroko\Checkout\Shared\Domain\ValueObject\Uuid;

class Product
{
    public function __construct(
        private readonly Uuid $id,
        private readonly string $description,
        private readonly Price $price
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function equals(Product $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => (string)$this->id,
            'description' => $this->description,
            'price' => $this->price->toArray()
        ];
    }
}
