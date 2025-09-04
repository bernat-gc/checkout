<?php

namespace BGC\Checkout\Shared\Domain\ValueObject;

use BGC\Checkout\Shared\Domain\ValueObject\Price;
use Exception;
use BGC\Checkout\Carts\Domain\Exception\InvalidPriceAmount;

class Price
{
    private const DEFAULT_CURRENCY = 'EUR';

    public function __construct(
        private readonly int $centsAmount,
        private readonly string $currency
    ) {
    }

    public static function fromAmount(float $amount, string $currency): Price
    {
        $this->ensureValidAmount($amount);
        return new Price($amount * 100, $currency);
    }

    private function ensureValidAmount(float $amount): void
    {
        if (((int)($amount * 100)) / 100 != $amount) {
            throw new InvalidPriceAmount();
        }
    }

    public function centsAmount(): int
    {
        return $this->centsAmount;
    }

    public function amount(): float
    {
        return $this->centsAmount / 100;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public static function sum(Price ...$prices): Price
    {
        $centsAmount = 0;
        $currency = self::DEFAULT_CURRENCY;

        foreach ($prices as $price) {
            if ($currency && $price->currency() != $currency) {
                throw new Exception("Cannot add prices of different currencies");
            } else {
                $currency = $price->currency();
            }

            $centsAmount += $price->centsAmount();
        }

        return new Price($centsAmount, $currency);
    }

    public function multiply(int $quantity): Price
    {
        return new Price($this->centsAmount * $quantity, $this->currency);
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->centsAmount,
            'currency' => $this->currency
        ];
    }
}
