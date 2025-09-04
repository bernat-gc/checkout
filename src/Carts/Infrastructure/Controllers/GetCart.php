<?php

namespace BGC\Checkout\Carts\Infrastructure\Controllers;

use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Carts\Application\Query\GetCartQuery;
use BGC\Checkout\Carts\Application\Response\GetCartResponse;
use BGC\Checkout\Shared\Infrastructure\Controller\BaseController;
use BGC\Checkout\Shared\Infrastructure\Exception\ValidationException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class GetCart extends BaseController
{
    public function __invoke(string $cartId): JsonResponse
    {
        try {
            $this->validate(['cart_id' => $cartId], $this->constraints());

            $query = new GetCartQuery($cartId);

            /** @var GetCartResponse $response */
            $response = $this->ask($query);

            return new JsonResponse($response->cart()->toArray());
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
}
