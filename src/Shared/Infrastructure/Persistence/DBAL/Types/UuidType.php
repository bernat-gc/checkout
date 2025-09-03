<?php

namespace BGC\Checkout\Shared\Infrastructure\Persistence\DBAL\Types;

use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UuidType extends StringType
{
    const TYPE_NAME = 'uuid';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Uuid($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string)$value;
    }
}
