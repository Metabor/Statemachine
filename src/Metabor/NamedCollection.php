<?php

namespace Metabor;

use MetaborStd\NamedCollectionInterface;
use MetaborStd\NamedInterface;

/**
 * @author Oliver Tischlinger
 */
class NamedCollection implements \IteratorAggregate, NamedCollectionInterface
{
    /**
     * @var array
     */
    private $collection = array();

    /**
     * @see MetaborStd.NamedCollectionInterface::add()
     */
    public function add(NamedInterface $object)
    {
        $this->collection[$object->getName()] = $object;
    }

    /**
     * @see MetaborStd.NamedCollectionInterface::get()
     */
    public function get($name)
    {
        return $this->collection[$name];
    }

    /**
     * @see MetaborStd.NamedCollectionInterface::getNames()
     */
    public function getNames()
    {
        return array_keys($this->collection);
    }

    /**
     * @see MetaborStd.NamedCollectionInterface::has()
     */
    public function has($name)
    {
        return isset($this->collection[$name]);
    }

    /**
     * @see \IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }
}
