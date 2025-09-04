<?php

namespace BGC\Checkout\Tests\ObjectMother;

use BGC\Checkout\Carts\Domain\CartItem;
use BGC\Checkout\Carts\Domain\ValueObject\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Tests\ObjectMother\ProductMother;

class CartItemMother
{
    public static function aCartItem(
        ?string $id = null,
        ?Product $product = null,
        ?int $quantity = null
    ): CartItem {
        $faker = \Faker\Factory::create();

        return new CartItem(
            new Uuid($id ?? $faker->uuid()),
            $product ?? ProductMother::aProduct(),
            $quantity ?? $faker->numberBetween(1, 3)
        );
    }

}
