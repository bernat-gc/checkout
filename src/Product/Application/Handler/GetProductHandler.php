<?php

namespace BGC\Checkout\Product\Application\Handler;

use BGC\Checkout\Product\Application\Query\GetProductQuery;
use BGC\Checkout\Product\Application\Response\GetProductResponse;
use BGC\Checkout\Product\Domain\Repository\ProductRepository;
use BGC\Checkout\Shared\Application\Handler\QueryHandlerInterface;

class GetProductHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {
    }

    public function __invoke(GetProductQuery $query): GetProductResponse
    {
        $product = $this->repository->findById($query->productId());

        return new GetProductResponse($product);
    }
}
