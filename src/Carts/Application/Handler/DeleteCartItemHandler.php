<?php

namespace BGC\Checkout\Carts\Application\Handler;

use BGC\Checkout\Carts\Application\Command\DeleteCartItemCommand;
use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\Repository\CartRepository;
use BGC\Checkout\Shared\Application\Events\EventPublisherInterface;
use BGC\Checkout\Shared\Application\Handler\CommandHandlerInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class DeleteCartItemHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CartRepository $repository,
        private readonly EventPublisherInterface $publisher
    ) {
    }

    public function __invoke(DeleteCartItemCommand $command): void
    {
        $cart = $this->getCart($command->cartId());

        $cart->removeCartItem($command->cartItemId());

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
