<?php

namespace Siroko\Checkout\Tests\ObjectMother;

use Siroko\Checkout\Carts\Domain\CartItem;
use Siroko\Checkout\Shared\Domain\ValueObject\Uuid;
use Siroko\Checkout\Tests\ObjectMother\ProductMother;

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
