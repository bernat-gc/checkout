<?php

/**
 * Cart class
 */

namespace Siroko\Checkout\Carts\Domain;

use DateTimeImmutable;
use Siroko\Checkout\Carts\Domain\CartItem;
use Siroko\Checkout\Carts\Domain\Collection\CartItems;
use Siroko\Checkout\Carts\Domain\Event\CartCreated;
use Siroko\Checkout\Carts\Domain\Event\CartItemsAdded;
use Siroko\Checkout\Carts\Domain\ValueObject\Price;
use Siroko\Checkout\Shared\Domain\AggregateRoot;
use Siroko\Checkout\Shared\Domain\ValueObject\Uuid;

class Cart extends AggregateRoot
{
	public function __construct(
		private Uuid $id,
		private Uuid $userId,
		private CartItems $items,
		private DateTimeImmutable $createdAt,
		private DateTimeImmutable $updatedAt
	) {
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
			new DateTimeImmutable(),
			new DateTimeImmutable()
		);
	}

	public function getTotalPrice(): Price
	{
		return $this->items->getTotalPrice();
	}

	public function addItem(CartItem $item): void
	{
		$this->items->addItem($item);

		$this->record(new CartItemsAdded(
			(string)$this->id,
			['items' => $this->items->toArray()]
		));
	}

	public function addItems(CartItem ...$items): void
	{
		foreach ($items as $item) {
			$this->items->addItem($item);
		}

		$this->record(new CartItemsAdded(
			(string)$this->id,
			['items' => $this->items->toArray()]
		));
	}
}
