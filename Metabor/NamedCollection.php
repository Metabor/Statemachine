<?php
namespace Metabor;
use MetaborInterface\NamedInterface;
use MetaborInterface\NamedCollectionInterface;
use IteratorAggregate;
use ArrayIterator;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class NamedCollection implements IteratorAggregate, NamedCollectionInterface
{

    /**
     *
     * @var array
     */
    private $collection = array();

    /**
     *
     * @see MetaborInterface.NamedCollectionInterface::add()
     */
    public function add (NamedInterface $object)
    {
        $this->collection[$object->getName()] = $object;
    }

    /**
     *
     * @see MetaborInterface.NamedCollectionInterface::get()
     */
    public function get ($name)
    {
        return $this->collection[$name];
    }

    /**
     *
     * @see MetaborInterface.NamedCollectionInterface::getNames()
     */
    public function getNames ()
    {
        return array_keys($this->collection);
    }

    /**
     *
     * @see MetaborInterface.NamedCollectionInterface::has()
     */
    public function has ($name)
    {
        return isset($this->collection[$name]);
    }

    /**
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator ()
    {
        return new ArrayIterator($this->collection);
    }

}