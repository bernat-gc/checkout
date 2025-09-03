<?php

namespace BGC\Checkout\Shared\Domain\Exception;

use Exception;

class InvalidClassForItem extends Exception
{
    public function __construct(
        string $collectionClass,
        string $expectedClass,
        string $actualClass
    ) {
        parent::__construct(sprintf(
            '%s collection expects its items to be of class %s. But %s given.',
            $collectionClass,
            $expectedClass,
            $actualClass
        ));
    }
}
