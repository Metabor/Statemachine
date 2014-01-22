<?php
namespace Metabor\Statemachine;
use MetaborStd\Statemachine\TransitionInterface;
use MetaborStd\Statemachine\StateInterface;
use Metabor\Named;
use MetaborStd\Statemachine\ProcessInterface;
use Exception;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Process extends Named implements ProcessInterface
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
    private $initinalState;

    /**
     *
     * @param string $name            
     * @param StateInterface $initinalState            
     */
    public function __construct($name, StateInterface $initinalState)
    {
        parent::__construct($name);
        $this->initinalState = $initinalState;
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
                        'There is already a different state with name "' . $name . '"');
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
        $this->addState($this->initinalState);
    }

    /**
     *
     * @see MetaborStd\Statemachine.ProcessInterface::getInitialState()
     */
    public function getInitialState()
    {
        return $this->initinalState;
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

}
