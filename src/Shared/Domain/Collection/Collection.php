<?php

namespace Siroko\Checkout\Shared\Domain\Collection;

abstract class Collection
{
    protected $items;

    abstract protected function itemClass(): string;

    public function __construct(
        ...$items
    ) {
        array_walk($items, [$this, 'ensureItemOfClass']);

        $this->items = $items;
    }

    private function ensureItemOfClass($item): void
    {
        $class = $this->itemClass();

        if (!($item instanceof $class)) {
            throw new InvalidClassForItem(
                static::class,
                $this->itemClass(),
                get_class($item)
            );
        }
    }

    protected function add($item): void
    {
        $this->ensureItemOfClass($item);
        $this->items[] = $item;
    }
}
