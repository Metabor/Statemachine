<?php
namespace Metabor\Event;

use Metabor\KeyValue\Nullable;
use Metabor\Observer\Subject;
use MetaborStd\ArrayConvertableInterface;
use MetaborStd\Event\EventInterface;
use MetaborStd\MetadataInterface;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *
 */
class Event extends Subject implements EventInterface, ArrayAccess, MetadataInterface
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var array
     */
    private $invokeArgs = array();

    /**
     *
     * @var \ArrayAccess
     */
    private $metadata;

    /**
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct();
        $this->name = $name;
        $this->metadata = new Nullable();
    }

    /**
     *
     * @see MetaborStd\Event.EventInterface::getInvokeArgs()
     */
    public function getInvokeArgs()
    {
        return $this->invokeArgs;
    }

    /**
     */
    public function __invoke()
    {
        $this->invokeArgs = func_get_args();
        $this->notify();
        $this->invokeArgs = array();
    }

    /**
     *
     * @see Metabor.Named::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return $this->metadata->offsetExists($offset);
    }

    /**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->metadata->offsetGet($offset);
    }

    /**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $this->metadata->offsetSet($offset, $value);
    }

    /**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        $this->metadata->offsetUnset($offset);
    }

    /**
     * @see \MetaborStd\MetadataInterface::getMetaData()
     */
    public function getMetaData()
    {
        if ($this->metadata instanceof MetadataInterface) {
            return $this->metadata->getMetaData();
        } elseif ($this->metadata instanceof ArrayConvertableInterface) {
            return $this->metadata->toArray();
        } else {
            throw new \RuntimeException('Unable to get MetaData!');
        }
    }

}
