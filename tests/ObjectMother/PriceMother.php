<?php

namespace BGC\Checkout\Tests\ObjectMother;

use BGC\Checkout\Shared\Domain\ValueObject\Price;

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
