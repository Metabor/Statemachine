<?php
namespace Metabor\Statemachine;

use Metabor\NamedCollection;
use Metabor\Statemachine\Util\StateCollectionMerger;
use MetaborStd\MergeableInterface;
use MetaborStd\Statemachine\StateCollectionInterface;
use MetaborStd\Statemachine\StateInterface;

/**
 *
 * @author Oliver Tischlinger
 *
 */
class StateCollection implements StateCollectionInterface, MergeableInterface
{

    /**
     *
     * @var NamedCollection
     */
    private $states;

    /**
     */
    public function __construct()
    {
        $this->states = new NamedCollection();
    }

    /**
     *
     * @see MetaborStd\Statemachine.StateCollectionInterface::getState()
     */
    public function getState($name)
    {
        return $this->states->get($name);
    }

    /**
     *
     * @see MetaborStd\Statemachine.StateCollectionInterface::getStates()
     */
    public function getStates()
    {
        return $this->states->getIterator();
    }

    /**
     *
     * @see MetaborStd\Statemachine.StateCollectionInterface::hasState()
     */
    public function hasState($name)
    {
        return $this->states->has($name);
    }

    /**
     *
     * @param StateInterface $state
     */
    public function addState(StateInterface $state)
    {
        $this->states->add($state);
    }

    /**
     * @see \MetaborStd\MergeableInterface::merge()
     */
    public function merge($source)
    {
        $merger = new StateCollectionMerger($this);
        $merger->merge($source);
    }
}
