<?php

namespace Siroko\Checkout\Tests\ObjectMother;

use Siroko\Checkout\Carts\Domain\Cart;
use Siroko\Checkout\Shared\Domain\ValueObject\Uuid;

class CartMother
{
    public static function aCartWithoutItems(
        ?string $id = null,
        ?string $userId = null
    ): Cart {
        $faker = \Faker\Factory::create();

        $cart = Cart::createCart(
            new Uuid($id ?? $faker->uuid()),
            new Uuid($user_id ?? $faker->uuid())
        );
        $cart->pullDomainEvents();

        return $cart;
    }
}
