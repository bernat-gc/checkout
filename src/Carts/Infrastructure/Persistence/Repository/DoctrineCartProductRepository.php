<?php

namespace BGC\Checkout\Carts\Infrastructure\Persistence\Repository;

use BGC\Checkout\Carts\Domain\Repository\CartProductRepository;
use BGC\Checkout\Carts\Domain\Entity\Product as CartProduct;
use BGC\Checkout\Product\Domain\Product;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Shared\Infrastructure\Persistence\Repository\AbstractDoctrineRepository;

class DoctrineCartProductRepository extends AbstractDoctrineRepository implements CartProductRepository
{
    private const CLASS_NAME = Product::class;

    public function className(): string
    {
        return self::CLASS_NAME;
    }

    public function findById(Uuid $productId): ?CartProduct
    {
        return $this->transform(
            $this->repository->find(['id' => (string)$productId])
        );
    }

    private function transform(?Product $product): ?CartProduct
    {
        if ($product instanceof Product) {
            return new CartProduct(
                $product->id(),
                $product->description(),
                $product->price()
            );
        }

        return null;
    }
}
