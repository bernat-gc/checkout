<?php

namespace BGC\Checkout\Carts\Infrastructure\Persistence\Repository;

use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\Repository\CartRepository;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Shared\Infrastructure\Persistence\Repository\AbstractDoctrineRepository;

class DoctrineCartRepository extends AbstractDoctrineRepository implements CartRepository
{
    public function className(): string
    {
        return Cart::class;
    }

    public function findById(Uuid $cartId): ?Cart
    {
        return $this->repository->find((string) $cartId);
    }

    public function save(Cart $cart): void
    {
        $this->entityManager->persist($cart);
    }
}
