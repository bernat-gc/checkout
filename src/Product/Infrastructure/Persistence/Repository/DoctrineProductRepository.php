<?php

namespace BGC\Checkout\Product\Infrastructure\Persistence\Repository;

use BGC\Checkout\Product\Domain\Product;
use BGC\Checkout\Product\Domain\Repository\ProductRepository;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Shared\Infrastructure\Persistence\Repository\AbstractDoctrineRepository;

class DoctrineProductRepository extends AbstractDoctrineRepository implements ProductRepository
{
    public function className(): string
    {
        return Product::class;
    }

    public function findById(Uuid $productId): ?Product
    {
        return $this->repository->find((string) $productId);
    }
}
