<?php

namespace BGC\Checkout\Carts\Application\Handler;

use BGC\Checkout\Carts\Application\Command\AddItemCommand;
use BGC\Checkout\Carts\Application\Exception\CartNotFound;
use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\CartItem;
use BGC\Checkout\Carts\Domain\Entity\Product;
use BGC\Checkout\Carts\Domain\Repository\CartProductRepository;
use BGC\Checkout\Carts\Domain\Repository\CartRepository;
use BGC\Checkout\Product\Application\Exception\ProductNotFound;
use BGC\Checkout\Shared\Application\Events\EventPublisherInterface;
use BGC\Checkout\Shared\Application\Handler\CommandHandlerInterface;
use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;

class AddItemHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CartRepository $repository,
        private readonly CartProductRepository $productRepository,
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

    private function findProduct(Uuid $productId): Product
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new ProductNotFound((string) $productId);
        }

        return $product;
    }

    private function buildItem(AddItemCommand $command): CartItem
    {
        $product = $this->findProduct($command->productId());

        return new CartItem(
            $command->id(),
            $product,
            $command->quantity()
        );
    }
}
