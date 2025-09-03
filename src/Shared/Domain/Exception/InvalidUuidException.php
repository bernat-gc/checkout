<?php

/**
 * Domain exception for invalid uuid strings
 */

namespace BGC\Checkout\Shared\Domain\Exception;

use Exception;

final class InvalidUuidException extends Exception
{

    public function __construct(string $value)
    {
        parent::__construct(sprintf("The string %s is not a valid uuid.", $value));
    }
}
