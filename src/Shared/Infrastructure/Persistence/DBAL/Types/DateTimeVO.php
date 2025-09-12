<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Infrastructure\Persistence\DBAL\Types;

use BGC\Checkout\Shared\Domain\ValueObject\DateTimeValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeImmutableType;

class DateTimeVO extends DateTimeImmutableType
{
    const TYPE_NAME = 'datetime_vo';

    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $datetime = parent::convertToPHPValue($value, $platform);

        return $datetime ? new DateTimeValueObject($datetime) : $datetime;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue($value?->toDateTimeImmutable(), $platform);
    }
}
