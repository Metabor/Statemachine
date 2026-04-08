<?php

namespace Metabor\Statemachine\Util;

use Metabor\Statemachine\State;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Transition;
use MetaborStd\Event\EventInterface;
use MetaborStd\Statemachine\ConditionInterface;
use MetaborStd\Statemachine\StateCollectionInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class SetupHelper
{
    protected StateCollectionInterface $stateCollection;

    public function __construct(StateCollectionInterface $stateCollection)
    {
        $this->stateCollection = $stateCollection;
    }

    protected function createState(string $name): StateInterface
    {
        return new State($name);
    }

    public function findOrCreateState(string $name): StateInterface
    {
        if (!$this->stateCollection->hasState($name)) {
            if ($this->stateCollection instanceof StateCollection) {
                $this->stateCollection->addState($this->createState($name));
            } else {
                throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
            }
        }

        return $this->stateCollection->getState($name);
    }

    protected function findTransition(StateInterface $sourceState, StateInterface $targetState, ?string $eventName = null, ?ConditionInterface $condition = null): ?TransitionInterface
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

        return null;
    }

    protected function addTransition(StateInterface $sourceState, TransitionInterface $sourceTransition): void
    {
        if ($sourceState instanceof State) {
            $sourceState->addTransition($sourceTransition);
        } else {
            throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
        }
    }

    public function createTransition(StateInterface $sourceState, StateInterface $targetState, ?string $eventName = null, ?ConditionInterface $condition = null): TransitionInterface
    {
        return new Transition($targetState, $eventName, $condition);
    }

    public function findOrCreateTransition(string $sourceStateName, string $targetStateName, ?string $eventName = null, ?ConditionInterface $condition = null): TransitionInterface
    {
        $sourceState = $this->findOrCreateState($sourceStateName);
        $targetState = $this->findOrCreateState($targetStateName);
        $transition = $this->findTransition($sourceState, $targetState, $eventName, $condition);
        if (!$transition) {
            $transition = $this->createTransition($sourceState, $targetState, $eventName, $condition);
            $this->addTransition($sourceState, $transition);
        }

        return $transition;
    }

    public function createEvent(StateInterface $sourceState, string $eventName): EventInterface
    {
        if ($sourceState instanceof State) {
            return $sourceState->getEvent($eventName);
        } else {
            throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
        }
    }

    public function findOrCreateEvent(string $sourceStateName, string $eventName): EventInterface
    {
        $sourceState = $this->findOrCreateState($sourceStateName);
        if ($sourceState->hasEvent($eventName)) {
            return $sourceState->getEvent($eventName);
        } else {
            return $this->createEvent($sourceState, $eventName);
        }
    }

    /**
     * If there is no Transition from the SourceState with this Event use addCommandAndSelfTransition().
     */
    public function addCommand(string $sourceStateName, string $eventName, \SplObserver $command): void
    {
        $this->findOrCreateEvent($sourceStateName, $eventName)->attach($command);
    }

    public function addCommandAndSelfTransition(string $sourceStateName, string $eventName, \SplObserver $command): void
    {
        $this->addCommand($sourceStateName, $eventName, $command);
        $this->findOrCreateTransition($sourceStateName, $sourceStateName, $eventName);
    }
}
