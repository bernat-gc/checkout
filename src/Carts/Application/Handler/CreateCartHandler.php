<?php

namespace BGC\Checkout\Carts\Application\Handler;

use BGC\Checkout\Carts\Application\Command\CreateCartCommand;
use BGC\Checkout\Carts\Application\Exception\CartAlreadyCreated;
use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\Repository\CartRepository;
use BGC\Checkout\Shared\Application\Events\EventPublisherInterface;
use BGC\Checkout\Shared\Application\Handler\CommandHandlerInterface;

class CreateCartHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CartRepository $repository,
        private readonly EventPublisherInterface $publisher
    ) {
    }

    /**
     * @throws CartAlreadyCreated
     */
    public function __invoke(CreateCartCommand $command): void
    {
        $this->ensureCartNotAlreadyCreated($command);

        $cart = Cart::createCart(
            $command->cartId(),
            $command->userId()
        );

        $this->repository->save($cart);
        $this->publisher->publish(...$cart->pullDomainEvents());
    }

    /**
     * @throws CartAlreadyCreated
     */
    private function ensureCartNotAlreadyCreated(CreateCartCommand $command): void
    {
        $cart = $this->repository->findById($command->cartId());

        if ($cart) {
            throw new CartAlreadyCreated();
        }
    }
}
