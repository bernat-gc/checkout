<?php

namespace Siroko\Checkout\Tests\ObjectMother;

use Siroko\Checkout\Carts\Domain\ValueObject\Price;

class PriceMother
{
    public static function aPrice(
        ?int $centsAmount = null,
        string $currency = 'EUR'
    )
    {
        $faker = \Faker\Factory::create();

        return new Price(
            $centsAmount ?? $faker->numberBetween(100, 1000),
            $currency
        );
    }
}
