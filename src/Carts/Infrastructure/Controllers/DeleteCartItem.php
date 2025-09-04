<?php

namespace BGC\Checkout\Carts\Infrastructure\Controllers;

use BGC\Checkout\Carts\Application\Command\DeleteCartItemCommand;
use BGC\Checkout\Carts\Application\Command\ModifyCartItemCommand;
use BGC\Checkout\Carts\Application\Exception\CartItemNotFound;
use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Shared\Infrastructure\Controller\BaseController;
use BGC\Checkout\Shared\Infrastructure\Exception\ValidationException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteCartItem extends BaseController
{
    public function __invoke(string $cartId, string $cartItemId): Response
    {
        try {
            $input = array_merge(
                ['cart_id' => $cartId, 'cart_item_id' => $cartItemId]
            );

            $this->validate($input, $this->constraints());

            $command = new DeleteCartItemCommand(
                $cartId,
                $cartItemId
            );

            $this->execute($command);

            return new Response('', Response::HTTP_NO_CONTENT);

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
            'cart_item_id' => [
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
            CartItemNotFound::class => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_INTERNAL_SERVER_ERROR
        };
    }
}
