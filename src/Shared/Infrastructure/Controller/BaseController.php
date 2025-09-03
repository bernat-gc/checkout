<?php

namespace BGC\Checkout\Shared\Infrastructure\Controller;

use BGC\Checkout\Shared\Infrastructure\Exception\ValidationException;
use JsonSerializable;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;
use Throwable;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected readonly MessageBusInterface $commandBus,
        protected readonly MessageBusInterface $queryBus,
        protected readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function validate(array $input, array $constraints): void
    {
        $validator = Validation::createValidator();
        $constraint = new Collection($constraints);
        $violations = $validator->validate($input, $constraint);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }

    protected function serializeException(Throwable $exception): array
    {
        $exception = $this->extractInnerException($exception);

        if ($exception instanceof JsonSerializable) {
            return $exception->jsonSerialize();
        }

        return [
            'class' => $exception::class,
            'message' => $exception->getMessage()
        ];
    }

    protected function extractInnerException(Throwable $exception)
    {
        if ($exception instanceof HandlerFailedException) {
            $wrappedExceptions = $exception->getWrappedExceptions();
            $exception = reset($wrappedExceptions);
        }

        return $exception;
    }
}
