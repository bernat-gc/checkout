<?php

namespace BGC\Checkout\Carts\Application\Handler;

use BGC\Checkout\Carts\Application\Command\OrderCartCommand;
use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\Repository\CartRepository;
use BGC\Checkout\Shared\Application\Events\EventPublisherInterface;
use BGC\Checkout\Shared\Application\Handler\CommandHandlerInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class OrderCartHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CartRepository $repository,
        private readonly EventPublisherInterface $publisher
    ) {
    }

    public function __invoke(OrderCartCommand $command): void
    {
        $cart = $this->getCart($command->cartId());

        $cart->order();

        $this->repository->save($cart);
        $this->publisher->publish(...$cart->pullDomainEvents());
    }

    private function getCart(Uuid $cartId): Cart
    {
        $cart = $this->repository->findById($cartId);

        if (!$cart) {
            throw new CartNotFound((string)$cartId);
        }

        return $cart;
    }
}
