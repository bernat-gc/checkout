<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\Exception;

use Exception;

class StringTooLong extends Exception
{
    public function __construct(int $length, int $maxLength)
    {
        parent::__construct(sprintf(
            "String of length %d exceeds max length of %d",
            $length,
            $maxLength
        ));
    }
}
