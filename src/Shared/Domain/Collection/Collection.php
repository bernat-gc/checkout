<?php

namespace BGC\Checkout\Shared\Domain\Collection;

use BGC\Checkout\Shared\Domain\Exception\InvalidClassForItem;
use BGC\Checkout\Shared\Domain\Exception\ItemNotFoundInCollection;
use Doctrine\Common\Collections\ArrayCollection;

abstract class Collection extends ArrayCollection
{
    abstract protected function itemClass(): string;

    public function __construct(
        ...$elements
    ) {
        array_walk($elements, [$this, 'ensureElementOfClass']);

        parent::__construct($elements);
    }

    protected function elements(): array
    {
        return parent::toArray();
    }

    private function ensureElementOfClass($element): void
    {
        $class = $this->itemClass();

        if (!($element instanceof $class)) {
            debug_print_backtrace();
            throw new InvalidClassForItem(
                static::class,
                $this->itemClass(),
                $element::class
            );
        }
    }

    public function add($element): void
    {
        $this->ensureElementOfClass($element);
        $this->set((string)$element->id(), $element);
    }

    public function removeItem(string $elementId): void
    {
        $removed = $this->remove($elementId);

        if (!$removed) {
            throw new ItemNotFoundInCollection();
        }
    }
}
