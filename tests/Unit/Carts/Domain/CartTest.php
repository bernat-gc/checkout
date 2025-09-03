<?php

namespace BGC\Checkout\Tests\Unit\Carts\Domain;

use BGC\Checkout\Carts\Domain\Cart;
use BGC\Checkout\Carts\Domain\Collection\CartItems;
use BGC\Checkout\Carts\Domain\Event\CartCreated;
use BGC\Checkout\Carts\Domain\Event\CartItemRemoved;
use BGC\Checkout\Carts\Domain\Event\CartItemsAdded;
use BGC\Checkout\Carts\Domain\Event\CartItemsModified;
use BGC\Checkout\Carts\Domain\Event\CartOrdered;
use BGC\Checkout\Carts\Domain\Exception\OrderedCartCannotBeModified;
use BGC\Checkout\Carts\Domain\ValueObject\CartStatus;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use BGC\Checkout\Tests\ObjectMother\CartItemMother;
use BGC\Checkout\Tests\ObjectMother\CartMother;
use Codeception\Test\Unit;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid as UuidGenerator;

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
            CartStatus::Shopping,
            $createdAt,
            $updatedAt
        );

        // Assert
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals($cart->status(), CartStatus::Shopping);
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

    public function testAnItemCanBeRemoved(): void
    {
        // Arrange
        $cart = CartMother::aCartWithItems(
            items: 3
        );
        $itemToRemoveId = $cart->items()->getKeys()[1];

        // Act
        $cart->removeCartItem(new Uuid($itemToRemoveId));

        // Assert
        $this->assertCount(2, $cart->items()->toArray());
        $events = $cart->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CartItemRemoved::class, $events[0]);
    }

    public function testAnItemCanBeModified(): void
    {
        // Arrange
        $cart = CartMother::aCartWithItems(
            items: 3
        );
        $itemToModifyId = $cart->items()->getKeys()[1];
        $itemToModify = $cart->items()->findById(new Uuid($itemToModifyId));
        $newQuantity = $itemToModify->quantity() + 2;

        // Act
        $cart->modifyCartItem(new Uuid($itemToModifyId), $newQuantity);

        // Assert
        $this->assertCount(3, $cart->items()->toArray());
        $this->assertEquals($newQuantity, $itemToModify->quantity());
        $events = $cart->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CartItemsModified::class, $events[0]);
    }

    public function testCartCanBeOrdered(): void
    {
        // Arrange
        $cart = CartMother::aCartWithItems();

        // Act
        $cart->order();

        // Assert
        $this->assertEquals(CartStatus::Ordered, $cart->status());
        $events = $cart->pullDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CartOrdered::class, $events[0]);
    }

    public function testItemCanNotBeAddedIfOrdered(): void
    {
        // Arrange
        $cart = CartMother::aCartWithItems(ordered: true);
        $cartItem = CartItemMother::aCartItem();

        $this->expectException(OrderedCartCannotBeModified::class);

        // Act
        $cart->addItem($cartItem);

        // Assert
        $this->expectNotToPerformAssertions();
    }


    public function testAnItemCanNotBeRemovedIfCartOrdered(): void
    {
        // Arrange
        $cart = CartMother::aCartWithItems(
            items: 3,
            ordered: true
        );
        $itemToRemoveId = $cart->items()->getKeys()[1];

        $this->expectException(OrderedCartCannotBeModified::class);

        // Act
        $cart->removeCartItem(new Uuid($itemToRemoveId));

        // Assert
        $this->expectNotToPerformAssertions();
    }

    public function testAnItemCanNotBeModifiedIfCartOrdered(): void
    {
        // Arrange
        $cart = CartMother::aCartWithItems(
            items: 3,
            ordered: true
        );
        $itemToModifyId = $cart->items()->getKeys()[1];
        $itemToModify = $cart->items()->findById(new Uuid($itemToModifyId));
        $newQuantity = $itemToModify->quantity() + 2;

        $this->expectException(OrderedCartCannotBeModified::class);

        // Act
        $cart->modifyCartItem(new Uuid($itemToModifyId), $newQuantity);

        // Assert
        $this->expectNotToPerformAssertions();
    }
}
