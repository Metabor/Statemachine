<?php
namespace Metabor\Statemachine;

use Exception;
use Metabor\Named;
use Metabor\Statemachine\Util\StateCollectionMerger;
use MetaborStd\MergeableInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\ProcessInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Process extends Named implements ProcessInterface, MergeableInterface
{

    /**
     *
     * @var StateCollection
     */
    private $states;

    /**
     *
     * @var StateInterface
     */
    private $initialState;

    /**
     *
     * @param string $name            
     * @param StateInterface $initialState            
     */
    public function __construct($name, StateInterface $initialState)
    {
        parent::__construct($name);
        $this->initialState = $initialState;
        $this->createCollection();
    }

    /**
     *
     * @param StateInterface $state            
     */
    protected function addState(StateInterface $state)
    {
        $name = $state->getName();
        if ($this->states->hasState($name)) {
            if ($this->states->getState($name) !== $state) {
                throw new Exception(
                        'There is already a different state with name "'
                                . $name . '"');
            }
        } else {
            $this->states->addState($state);
            /* @var $transition TransitionInterface */
            foreach ($state->getTransitions() as $transition) {
                $targetState = $transition->getTargetState();
                $this->addState($targetState);
            }
        }
    }

    /**
     */
    protected function createCollection()
    {
        $this->states = new StateCollection();
        $this->addState($this->initialState);
    }

    /**
     *
     * @see MetaborStd\Statemachine.ProcessInterface::getInitialState()
     */
    public function getInitialState()
    {
        return $this->initialState;
    }

    /**
     *
     * @see MetaborStd\Statemachine.ProcessInterface::getStates()
     */
    public function getStates()
    {
        return $this->states->getStates();
    }

    /**
     *
     * @param unknown_type $name            
     */
    public function getState($name)
    {
        return $this->states->getState($name);
    }

    /**
     *
     * @param string $name            
     * @return boolean
     */
    public function hasState($name)
    {
        return $this->states->hasState($name);
    }

    /**
     * @see \MetaborStd\MergeableInterface::merge()
     */
    public function merge($source)
    {
        $merger = new StateCollectionMerger($this->states);
        $merger->merge($source);
    }

}