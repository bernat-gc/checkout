<?php

namespace BGC\Checkout\Carts\Infrastructure\Controllers;

use BGC\Checkout\Carts\Application\Command\AddItemCommand;
use BGC\Checkout\Carts\Application\Command\CreateCartCommand;
use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Product\Application\Exception\ProductNotFound;
use BGC\Checkout\Product\Application\Query\GetProductQuery;
use BGC\Checkout\Product\Domain\Product;
use BGC\Checkout\Shared\Infrastructure\Controller\BaseController;
use BGC\Checkout\Shared\Infrastructure\Exception\ValidationException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\Constraints as Assert;

class PostCartItem extends BaseController
{
    public function __invoke(string $cartId, Request $request): Response
    {
        try {
            $input = $request->toArray();

            $this->validate($input, $this->constraints());

            $product = $this->getProduct($input['product_id']);

            if (!$product) {
                throw new ProductNotFound($input['product_id']);
            }

            $command = new AddItemCommand(
                $input['id'],
                $cartId,
                (string)$product->id(),
                $product->description(),
                $input['quantity'],
                $product->price()->centsAmount(),
                $product->price()->currency()
            );

            $this->execute($command);

            return new Response('', Response::HTTP_CREATED);

        } catch (Exception $exception) {
            return new JsonResponse(
                $this->serializeException($exception),
                $this->responseCodeMapping($exception)
            );
        }
    }

    private function getProduct(string $productId): ?Product
    {
        $query = new GetProductQuery($productId);

        $response = $this->ask($query);

        return $response->product();
    }

    private function constraints(): array
    {
        return [
            'id' => [
                new Assert\Required(),
                new Assert\Uuid(versions: [Assert\Uuid::V4_RANDOM])
            ],
            'product_id' => [
                new Assert\Required(),
                new Assert\Uuid(versions: [Assert\Uuid::V4_RANDOM])
            ],
            'quantity' => [
                new Assert\Type('integer'),
                new Assert\Positive()
            ]
        ];
    }

    private function responseCodeMapping(Exception $exception): int
    {
        $exception = $this->extractInnerException($exception);

        return match ($exception::class) {
            ValidationException::class => Response::HTTP_BAD_REQUEST,
            CartNotFound::class => Response::HTTP_NOT_FOUND,
            ProductNotFound::class => Response::HTTP_BAD_REQUEST,
            default => Response::HTTP_INTERNAL_SERVER_ERROR
        };
    }
}
