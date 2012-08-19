<?php
namespace Metabor\Statemachine;

use MetaborInterface\Statemachine\StateInterface;
use Metabor\NamedCollection;
use MetaborInterface\Statemachine\StateCollectionInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class StateCollection implements StateCollectionInterface
{

    /**
     *
     * @var NamedCollection
     */
    private $states;

    /**
     */
    public function __construct ()
    {
        $this->states = new NamedCollection();
    }

    /**
     *
     * @see MetaborInterface\Statemachine.StateCollectionInterface::getState()
     */
    public function getState ($name)
    {
        return $this->states->get($name);
    }

    /**
     *
     * @see MetaborInterface\Statemachine.StateCollectionInterface::getStates()
     */
    public function getStates ()
    {
        return $this->states->getIterator();
    }

    /**
     *
     * @see MetaborInterface\Statemachine.StateCollectionInterface::hasState()
     */
    public function hasState ($name)
    {
        return $this->states->has($name);
    }

    /**
     *
     * @param StateInterface $state            
     */
    public function addState (StateInterface $state)
    {
        $this->states->add($state);
    }

}