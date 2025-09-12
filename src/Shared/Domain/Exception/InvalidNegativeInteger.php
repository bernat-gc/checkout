<?php

declare(strict_types=1);

namespace BGC\Checkout\Shared\Domain\Exception;

use Exception;

class InvalidNegativeInteger extends Exception
{
    public function __construct()
    {
        parent::__construct("Invalid negative integer.");
    }
}
