<?php

namespace BGC\Checkout\Shared\Domain\Collection;

use BGC\Checkout\Shared\Domain\Exception\InvalidClassForItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\PersistentCollection;

abstract class Collection extends ArrayCollection implements DoctrineCollection
{
    abstract protected function itemClass(): string;

    private ?PersistentCollection $persistent;

    public function __construct(
        ...$elements
    ) {
        array_walk($elements, [$this, 'ensureElementOfClass']);
        $this->persistent = null;

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
        if ($this->persistent) {
            $this->persistent->set((string)$element->id(), $element);
        }
        parent::set((string)$element->id(), $element);
    }

    public function removeElement($element): bool
    {
        if ($this->persistent) {
            $this->persistent->removeElement($element);
        }
        return parent::removeElement($element);
    }

    public static function transform(?DoctrineCollection $items): DoctrineCollection
    {
        $class = get_called_class();

        if ($items instanceof PersistentCollection) {
            $elements = [];
            foreach ($items as $item) {
                array_push($elements, $item);
            }
            /** @var self $newCollection */
            $newCollection = new $class(...$elements);
            $newCollection->setPersistentCollection($items);
            return $newCollection;
        }
        if ($items) {
            return $items;
        }
        return new $class();
    }

    public function setPersistentCollection(PersistentCollection $collection): void
    {
        $this->persistent = $collection;
    }
}
