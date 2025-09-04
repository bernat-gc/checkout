<?php

namespace BGC\Checkout\Carts\Application\Handler;

use BGC\Checkout\Carts\Domain\CartItem;
use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\ValueObject\Product;
use BGC\Checkout\Carts\Domain\Repository\CartRepository;
use BGC\Checkout\Carts\Application\Command\AddItemCommand;
use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Shared\Application\Events\EventPublisherInterface;
use BGC\Checkout\Shared\Application\Handler\CommandHandlerInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class AddItemHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CartRepository $repository,
        private readonly EventPublisherInterface $publisher
    ) {
    }

    /**
     * @throws CartNotFound
     * @throws OrderedCartCannotBeModified
     */
    public function __invoke(AddItemCommand $command): void
    {
        $cart = $this->findCart($command->cartId());

        $item = $this->buildItem($command);

        $cart->addItem($item);

        $this->repository->save($cart);
        $this->publisher->publish(...$cart->pullDomainEvents());
    }

    /**
     * @throws CartNotFound
     */
    private function findCart(Uuid $cartId): Cart
    {
        $cart = $this->repository->findById($cartId);

        if (!$cart) {
            throw new CartNotFound((string)$cartId);
        }

        return $cart;
    }

    private function buildItem(AddItemCommand $command): CartItem
    {
        $product = new Product(
            $command->productId(),
            $command->description(),
            new Price(
                $command->centsAmount(),
                $command->currency()
            )
        );

        return new CartItem(
            $command->id(),
            $product,
            $command->quantity()
        );
    }
}
