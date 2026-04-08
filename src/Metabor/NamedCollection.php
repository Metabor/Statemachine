<?php

namespace Metabor;

use MetaborStd\NamedCollectionInterface;
use MetaborStd\NamedInterface;

/**
 * @author Oliver Tischlinger
 */
class NamedCollection implements NamedCollectionInterface, \IteratorAggregate
{
    private array $collection = [];

    public function add(NamedInterface $object): void
    {
        $this->collection[$object->getName()] = $object;
    }

    public function getNames(): array
    {
        return array_keys($this->collection);
    }

    public function has(string $name): bool
    {
        return isset($this->collection[$name]);
    }

    public function get(string $name): NamedInterface
    {
        return $this->collection[$name];
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->collection);
    }
}
