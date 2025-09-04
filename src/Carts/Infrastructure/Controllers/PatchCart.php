<?php

namespace BGC\Checkout\Carts\Infrastructure\Controllers;

use BGC\Checkout\Carts\Application\Command\ModifyCartItemCommand;
use BGC\Checkout\Carts\Application\Command\OrderCartCommand;
use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Carts\Domain\ValueObject\CartStatus;
use BGC\Checkout\Shared\Infrastructure\Controller\BaseController;
use BGC\Checkout\Shared\Infrastructure\Exception\ValidationException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class PatchCart extends BaseController
{
    private const ORDERED_STATUS = 'Ordered';

    public function __invoke(string $cartId, Request $request): Response
    {
        try {
            $input = array_merge(
                $request->toArray(),
                ['cart_id' => $cartId]
            );

            $this->validate($input, $this->constraints());

            if ($input['status'] == self::ORDERED_STATUS) {
                dump(__METHOD__);
                $command = new OrderCartCommand(
                    $cartId
                );

                $this->execute($command);

                return new Response('', Response::HTTP_NO_CONTENT);
            }

            return new Response('', Response::HTTP_BAD_REQUEST);

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
            'cart_id' => [
                new Assert\Required(),
                new Assert\Uuid(versions: [Assert\Uuid::V4_RANDOM])
            ],
            'status' => [
                new Assert\Choice(options: $this->statusOptions())
            ]
        ];
    }

    private function responseCodeMapping(Exception $exception): int
    {
        $exception = $this->extractInnerException($exception);

        return match ($exception::class) {
            ValidationException::class => Response::HTTP_BAD_REQUEST,
            CartNotFound::class => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_INTERNAL_SERVER_ERROR
        };
    }

    private function statusOptions(): array
    {
        return CartStatus::options();
    }
}
