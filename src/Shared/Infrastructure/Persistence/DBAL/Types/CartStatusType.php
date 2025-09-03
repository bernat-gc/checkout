<?php

namespace BGC\Checkout\Shared\Infrastructure\Persistence\DBAL\Types;

use BGC\Checkout\Carts\Domain\ValueObject\CartStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CartStatusType extends StringType
{
    const TYPE_NAME = 'cart_status';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return CartStatus::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->value;
    }
}
