<?php

namespace BGC\Checkout\Tests\ObjectMother;

use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\ValueObject\CartStatus;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Tests\ObjectMother\CartItemMother;

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

    public static function aCartWithItems(
        ?string $id = null,
        ?string $userId = null,
        null|array|int $items = null,
        bool $ordered = false
    ): Cart {
        $faker = \Faker\Factory::create();

        $cart = self::aCartWithoutItems($id, $userId);

        if (!$items) {
            $items = $faker->numberBetween(1, 3);
        }

        if (is_int($items)) {
            $itemsQuantity = $items;
            $items = [];
            for ($i = 0; $i < $itemsQuantity; $i++) {
                $items[] = CartItemMother::aCartItem();
            }
        }

        $cart->addItems(...$items);

        if ($ordered) {
            $cart->order();
        }

        $cart->pullDomainEvents();

        return $cart;
    }
}
