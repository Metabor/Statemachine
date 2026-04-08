<?php

namespace Metabor\Statemachine\Util;

use Metabor\Statemachine\State;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Transition;
use MetaborStd\MergeableInterface;
use MetaborStd\MetadataInterface;
use MetaborStd\Statemachine\ConditionInterface;
use MetaborStd\WeightedInterface;
use MetaborStd\Statemachine\StateCollectionInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class StateCollectionMerger implements MergeableInterface
{
    private StateCollectionInterface $targetCollection;
    private ?string $stateNamePrefix = null;

    public function __construct(StateCollectionInterface $targetCollection)
    {
        $this->targetCollection = $targetCollection;
    }

    public function getStateNamePrefix(): ?string
    {
        return $this->stateNamePrefix;
    }

    public function setStateNamePrefix(?string $stateNamePrefix): void
    {
        $this->stateNamePrefix = $stateNamePrefix;
    }

    public function getTargetCollection(): StateCollectionInterface
    {
        return $this->targetCollection;
    }

    protected function createState(string $name): StateInterface
    {
        return new State($name);
    }

    protected function addTransition(StateInterface $sourceState, TransitionInterface $sourceTransition): void
    {
        if ($sourceState instanceof State) {
            $sourceState->addTransition($sourceTransition);
        } else {
            throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
        }
    }

    protected function createCondition(TransitionInterface $sourceTransition): ?ConditionInterface
    {
        if ($sourceTransition->getConditionName()) {
            if ($sourceTransition instanceof Transition) {
                $condition = $sourceTransition->getCondition();
            } else {
                throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
            }
        } else {
            $condition = null;
        }

        return $condition;
    }

    protected function createTransition(TransitionInterface $sourceTransition): Transition
    {
        $targetStateName = $sourceTransition->getTargetState()->getName();
        $targetState = $this->findOrCreateState($targetStateName);
        $this->mergeMetadata($sourceTransition->getTargetState(), $targetState);
        $eventName = $sourceTransition->getEventName();

        $condition = $this->createCondition($sourceTransition);

        $transition = new Transition($targetState, $eventName, $condition);
        if ($sourceTransition instanceof WeightedInterface) {
            $transition->setWeight($sourceTransition->getWeight());
        }

        return $transition;
    }

    protected function mergeMetadata(object $source, object $target): void
    {
        if ($source instanceof \ArrayAccess) {
            if ($target instanceof \ArrayAccess) {
                if ($source instanceof MetadataInterface) {
                    $metadata = $source->getMetadata();
                    foreach ($metadata as $offset => $value) {
                        $target->offsetSet($offset, $value);
                    }
                } else {
                    throw new \RuntimeException('Source had to make all metadata available!');
                }
            } else {
                throw new \RuntimeException('Source metadata can not be merged!');
            }
        }
    }

    protected function addState(StateInterface $state): void
    {
        if ($this->targetCollection instanceof StateCollection) {
            $this->targetCollection->addState($state);
        } else {
            throw new \InvalidArgumentException('TargetCollection has to be a StateCollection. Overwrite this method to implement a different type!');
        }
    }

    protected function findOrCreateState(string $name): StateInterface
    {
        $name = $this->stateNamePrefix . $name;
        if ($this->targetCollection->hasState($name)) {
            $targetState = $this->targetCollection->getState($name);
        } else {
            $targetState = $this->createState($name);
            $this->addState($targetState);
        }

        return $targetState;
    }

    protected function mergeState(StateInterface $sourceState): void
    {
        $name = $sourceState->getName();
        $targetState = $this->findOrCreateState($name);
        $this->mergeMetadata($sourceState, $targetState);

        /* @var $transition TransitionInterface */
        foreach ($sourceState->getTransitions() as $sourceTransition) {
            $targetTransition = $this->createTransition($sourceTransition);
            $this->addTransition($targetState, $targetTransition);
        }

        foreach ($sourceState->getEventNames() as $eventName) {
            $sourceEvent = $sourceState->getEvent($eventName);
            $targetEvent = $targetState->getEvent($eventName);

            $this->mergeMetadata($sourceEvent, $targetEvent);

            foreach ($sourceEvent->getObservers() as $observer) {
                $targetEvent->attach($observer);
            }
        }
    }

    protected function mergeStateCollection(StateCollectionInterface $source): void
    {
        /* @var $sourceState StateInterface */
        foreach ($source->getStates() as $sourceState) {
            $this->mergeState($sourceState);
        }
    }

    public function merge(mixed $source): void
    {
        if ($source instanceof StateCollectionInterface) {
            $this->mergeStateCollection($source);
        } elseif ($source instanceof StateInterface) {
            $this->mergeState($source);
        } elseif ($source instanceof \Traversable) {
            foreach ($source as $value) {
                $this->merge($value);
            }
        } else {
            throw new \InvalidArgumentException('Source can not be merged!');
        }
    }
}
