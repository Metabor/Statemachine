<?php

namespace Metabor\Statemachine\Util;

use Metabor\Statemachine\State;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Transition;
use MetaborStd\Statemachine\ConditionInterface;
use MetaborStd\Statemachine\StateCollectionInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class SetupHelper
{
    /**
     * @var StateCollectionInterface
     */
    protected $stateCollection;

    /**
     * @param StateCollectionInterface $stateCollection
     */
    public function __construct(StateCollectionInterface $stateCollection)
    {
        $this->stateCollection = $stateCollection;
    }

    /**
     * @param $name
     *
     * @return \MetaborStd\Statemachine\StateInterface
     *
     * @throws \Exception
     */
    public function findOrCreateState($name)
    {
        if (!$this->stateCollection->hasState($name)) {
            if ($this->stateCollection instanceof StateCollection) {
                $this->stateCollection->addState(new State($name));
            } else {
                throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
            }
        }

        return $this->stateCollection->getState($name);
    }

    /**
     * @param StateInterface     $sourceState
     * @param StateInterface     $targetState
     * @param string             $eventName
     * @param ConditionInterface $condition
     *
     * @return TransitionInterface
     */
    protected function findTransition(StateInterface $sourceState, StateInterface $targetState, $eventName = null, ConditionInterface $condition = null)
    {
        $conditionName = $condition ? $condition->getName() : null;
        /* @var $transition TransitionInterface */
        foreach ($sourceState->getTransitions() as $transition) {
            $hasSameTargetState = ($transition->getTargetState() === $targetState);
            $hasSameCondition = ($transition->getConditionName() == $conditionName);
            $hasSameEvent = ($transition->getEventName() == $eventName);
            if ($hasSameTargetState && $hasSameCondition && $hasSameEvent) {
                return $transition;
            }
        }
    }

    /**
     * @param StateInterface      $sourceState
     * @param TransitionInterface $sourceTransition
     *
     * @throws \InvalidArgumentException
     */
    protected function addTransition(StateInterface $sourceState, TransitionInterface $sourceTransition)
    {
        if ($sourceState instanceof State) {
            $sourceState->addTransition($sourceTransition);
        } else {
            throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
        }
    }

    /**
     * @param string             $sourceStateName
     * @param string             $targetStateName
     * @param string             $eventName
     * @param ConditionInterface $condition
     *
     * @return TransitionInterface
     */
    public function findOrCreateTransition($sourceStateName, $targetStateName, $eventName = null, ConditionInterface $condition = null)
    {
        $sourceState = $this->findOrCreateState($sourceStateName);
        $targetState = $this->findOrCreateState($targetStateName);
        $transition = $this->findTransition($sourceState, $targetState, $eventName, $condition);
        if (!$transition) {
            $transition = new Transition($targetState, $eventName, $condition);
            $this->addTransition($sourceState, $transition);
        }

        return $transition;
    }

    /**
     * If there is no Transition from the SourceState with this Event use addCommandAndSelfTransition().
     *
     * @param $sourceStateName
     * @param string  $eventName
     * @param \SplObserver $command
     */
    public function addCommand($sourceStateName, $eventName, \SplObserver $command)
    {
        $sourceState = $this->findOrCreateState($sourceStateName);
        $sourceState->getEvent($eventName)->attach($command);
    }

    /**
     * @param $sourceStateName
     * @param string  $eventName
     * @param \SplObserver $command
     */
    public function addCommandAndSelfTransition($sourceStateName, $eventName, \SplObserver $command)
    {
        $this->addCommand($sourceStateName, $eventName, $command);
        $this->findOrCreateTransition($sourceStateName, $sourceStateName, $eventName);
    }
}
