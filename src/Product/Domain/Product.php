<?php

namespace BGC\Checkout\Product\Domain;

use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Shared\Domain\AggregateRoot;
use BGC\Checkout\Shared\Domain\ValueObject\ProductDescription;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class Product extends AggregateRoot
{
    public function __construct(
        private Uuid $id,
        private ProductDescription $description,
        private Price $price
    ) {
    }

    public function description(): ProductDescription
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
