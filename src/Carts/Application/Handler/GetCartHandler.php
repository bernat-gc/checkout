<?php

namespace BGC\Checkout\Carts\Application\Handler;

use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Carts\Application\Query\GetCartQuery;
use BGC\Checkout\Carts\Application\Response\GetCartResponse;
use BGC\Checkout\Carts\Domain\Repository\CartRepository;
use BGC\Checkout\Shared\Application\Handler\QueryHandlerInterface;

class GetCartHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly CartRepository $repository
    ) {
    }

    public function __invoke(GetCartQuery $query): GetCartResponse
    {
        $cart = $this->repository->findById($query->cartId());

        if (!$cart) {
            throw new CartNotFound((string)$query->cartId());
        }

        return new GetCartResponse($cart);
    }
}
