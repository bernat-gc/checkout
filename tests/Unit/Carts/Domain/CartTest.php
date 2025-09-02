<?php

namespace Siroko\Checkout\Tests\Unit\Carts\Domain;

use Codeception\Test\Unit;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid as UuidGenerator;
use Siroko\Checkout\Carts\Domain\Cart;
use Siroko\Checkout\Carts\Domain\Collection\CartItems;
use Siroko\Checkout\Carts\Domain\Event\CartCreated;
use Siroko\Checkout\Carts\Domain\Event\CartItemsAdded;
use Siroko\Checkout\Shared\Domain\ValueObject\Uuid;
use Siroko\Checkout\Tests\ObjectMother\CartItemMother;
use Siroko\Checkout\Tests\ObjectMother\CartMother;

class CartTest extends Unit
{
    public function testCartCanBeCreated(): void
    {
        // Arrange
        $cart_id = new Uuid(UuidGenerator::uuid4());
        $user_id = new Uuid(UuidGenerator::uuid4());
        $items = new CartItems();
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();

        // Act
        $cart = new Cart(
            $cart_id,
            $user_id,
            $items,
            $createdAt,
            $updatedAt
        );

        // Assert
        $this->assertInstanceOf(Cart::class, $cart);
        $events = $cart->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CartCreated::class, $events[0]);
    }

    public function testItemCanBeAdded(): void
    {
        // Arrange
        $cart = CartMother::aCartWithoutItems();
        $cartItem = CartItemMother::aCartItem();

        // Act
        $cart->addItem($cartItem);

        // Assert
        $this->assertEquals(
            $cartItem->getTotalPrice()->centsAmount(),
            $cart->getTotalPrice()->centsAmount()
        );
        $events = $cart->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CartItemsAdded::class, $events[0]);
    }

    public function testMultipleItemsCanBeAdded(): void
    {
        // Arrange
        $cart = CartMother::aCartWithoutItems();
        $cartItem1 = CartItemMother::aCartItem();
        $cartItem2 = CartItemMother::aCartItem();

        // Act
        $cart->addItems($cartItem1, $cartItem2);

        // Assert
        $amount = $cartItem1->getTotalPrice()->centsAmount() + $cartItem2->getTotalPrice()->centsAmount();
        $this->assertEquals(
            $amount,
            $cart->getTotalPrice()->centsAmount()
        );
        $events = $cart->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CartItemsAdded::class, $events[0]);
    }
}
