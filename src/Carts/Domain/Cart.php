<?php

/**
 * Cart class
 */

namespace BGC\Checkout\Carts\Domain;

use BGC\Checkout\Carts\Domain\CartItem;
use BGC\Checkout\Carts\Domain\Collection\CartItems;
use BGC\Checkout\Carts\Domain\Event\CartCreated;
use BGC\Checkout\Carts\Domain\Event\CartItemRemoved;
use BGC\Checkout\Carts\Domain\Event\CartItemsAdded;
use BGC\Checkout\Carts\Domain\Event\CartItemsModified;
use BGC\Checkout\Carts\Domain\Event\CartOrdered;
use BGC\Checkout\Carts\Domain\Exception\OrderedCartCannotBeModified;
use BGC\Checkout\Carts\Domain\ValueObject\CartStatus;
use BGC\Checkout\Shared\Domain\ValueObject\Price;
use BGC\Checkout\Shared\Domain\AggregateRoot;
use BGC\Checkout\Shared\Domain\Exception\ItemNotFoundInCollection;
use BGC\Checkout\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Doctrine\ORM\PersistentCollection;

class Cart extends AggregateRoot
{
	public function __construct(
		private Uuid $id,
		private Uuid $userId,
		private CartItems|PersistentCollection $items,
		private CartStatus $status
	) {
		parent::__construct();

		$this->record(new CartCreated((string)$id, ['user_id' => (string)$userId]));
	}

	public static function createCart(
		Uuid $id,
		Uuid $userId
	): static {
		return new static(
			$id,
			$userId,
			new CartItems(),
			CartStatus::Shopping,
			new DateTimeImmutable(),
			new DateTimeImmutable()
		);
	}

	public function getTotalPrice(): Price
	{
		return $this->items->getTotalPrice();
	}

	public function items(): CartItems
	{
		if ($this->items instanceof PersistentCollection) {
			$this->items = new CartItems(
				...$this->items->toArray()
			);
		}
		return $this->items;
	}

	public function addItem(CartItem $item): void
	{
		$this->ensureCanBeModified();

		$item->setCart($this);
		$this->items()->addItem($item);

		$this->record(new CartItemsAdded(
			(string)$this->id,
			['items' => $this->items()->toArray()]
		));
	}

	public function addItems(CartItem ...$items): void
	{
		$this->ensureCanBeModified();

		foreach ($items as $item) {
			$this->items()->addItem($item);
			$item->setCart($this);
		}

		$this->record(new CartItemsAdded(
			(string)$this->id,
			['items' => $this->items()->toArray()]
		));
	}

	public function removeCartItem(Uuid $cartItemId): void
	{
		$this->ensureCanBeModified();

		$this->items()->removeItem((string)$cartItemId);

		$this->record(new CartItemRemoved(
			(string)$this->id,
			['items' => $this->items()->toArray()]
		));
	}

	public function modifyCartItem(Uuid $itemId, int $newQuantity): void
	{
		$this->ensureCanBeModified();

		$item = $this->items()->findById($itemId);

		if (!$item) {
			throw new ItemNotFoundInCollection();
		}

		$item->modifyQuantity($newQuantity);

		$this->record(new CartItemsModified(
			(string)$this->id,
			['items' => $this->items()->toArray()]
		));
	}

	public function status(): CartStatus
	{
		return $this->status;
	}

	public function order(): void
	{
		$this->status = CartStatus::Ordered;

		$this->record(new CartOrdered(
			(string)$this->id,
			['status' => $this->status->value]
		));
	}

	private function ensureCanBeModified(): void
	{
		if (!$this->status->allowModification()) {
			throw new OrderedCartCannotBeModified();
		}
	}
}
