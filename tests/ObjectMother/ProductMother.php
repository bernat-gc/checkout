<?php

namespace BGC\Checkout\Tests\ObjectMother;

use BGC\Checkout\Carts\Domain\ValueObject\Price;
use BGC\Checkout\Carts\Domain\ValueObject\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class ProductMother
{
    public static function aProduct(
        ?string $id = null,
        ?string $description = null,
        ?Price $price = null
    ): Product {
        $faker = \Faker\Factory::create();

        return new Product(
            new Uuid($id ?? $faker->uuid()),
            $description ?? $faker->word(),
            $price ?? PriceMother::aPrice()
        );
    }
}
