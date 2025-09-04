<?php

namespace BGC\Checkout\Shared\Infrastructure\Controller;

use JsonSerializable;
use Throwable;
use BGC\Checkout\Shared\Application\Handler\Command\CommandInterface;
use BGC\Checkout\Shared\Application\Handler\Query\QueryInterface;
use BGC\Checkout\Shared\Application\Handler\Query\ResponseInterface;
use BGC\Checkout\Shared\Infrastructure\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;

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

    protected function execute(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }

    protected function ask(QueryInterface $query): ResponseInterface
    {
        $responseEnvelope = $this->queryBus->dispatch($query);
        $handledStamp = $responseEnvelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}
