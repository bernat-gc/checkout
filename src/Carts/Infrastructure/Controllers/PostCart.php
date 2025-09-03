<?php

namespace BGC\Checkout\Carts\Infrastructure\Controllers;

use BGC\Checkout\Carts\Application\Command\CreateCartCommand;
use BGC\Checkout\Carts\Application\Exception\CartAlreadyCreated;
use BGC\Checkout\Shared\Infrastructure\Controller\BaseController;
use BGC\Checkout\Shared\Infrastructure\Exception\ValidationException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class PostCart extends BaseController
{
    public function __invoke(Request $request): Response
    {
        try {
            $input = $request->toArray();

            $this->validate($input, $this->constraints());

            $command = new CreateCartCommand(
                $input['id'],
                $input['user_id']
            );

            $this->commandBus->dispatch($command);

            return new Response('', Response::HTTP_CREATED);

        } catch (Exception $exception) {
            return new JsonResponse(
                $this->serializeException($exception),
                $this->responseCodeMapping($exception)
            );
        }
    }

    private function constraints(): array
    {
        return [
            'id' => [
                new Assert\Required(),
                new Assert\Uuid(versions: [Assert\Uuid::V4_RANDOM])
            ],
            'user_id' => [
                new Assert\Required(),
                new Assert\Uuid(versions: [Assert\Uuid::V4_RANDOM])
            ]
        ];
    }

    private function responseCodeMapping(Exception $exception): int
    {
        $exception = $this->extractInnerException($exception);

        return match ($exception::class) {
            ValidationException::class => Response::HTTP_BAD_REQUEST,
            CartAlreadyCreated::class => Response::HTTP_CONFLICT,
            default => Response::HTTP_INTERNAL_SERVER_ERROR
        };
    }
}
