<?php

namespace BGC\Checkout\Product\Domain;

use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Shared\Domain\AggregateRoot;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class Product extends AggregateRoot
{
    public function __construct(
        private Uuid $id,
        private string $description,
        private Price $price
    ) {
    }

    public function description(): string
    {
        return $this->description;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}
