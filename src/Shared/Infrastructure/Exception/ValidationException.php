<?php

namespace BGC\Checkout\Shared\Infrastructure\Exception;

use Exception;
use JsonSerializable;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends Exception implements JsonSerializable
{
    public function __construct(
        private readonly ConstraintViolationList $violationList)
    {
        parent::__construct('');
    }

    public function jsonSerialize(): array
    {
        $serialization = [];

        foreach ($this->violationList as $violation) {
            /** @var ConstraintViolation $violation */
            $serialization[] = [
                'message' => $violation->getMessage(),
                'path' => $violation->getPropertyPath()
            ];
        }

        return $serialization;
    }
}
