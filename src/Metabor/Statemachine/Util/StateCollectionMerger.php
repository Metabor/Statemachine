<?php
namespace Metabor\Statemachine\Util;

use Metabor\Statemachine\State;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Transition;
use MetaborStd\MergeableInterface;
use MetaborStd\MetadataInterface;
use MetaborStd\Statemachine\StateCollectionInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;
use MetaborStd\Statemachine\ProcessInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class StateCollectionMerger implements MergeableInterface
{
    /**
     * @var ProcessInterface
     */
    private $targetCollection;

    /**
     * 
     * @param StateCollectionInterface $targetCollection
     */
    public function __construct(StateCollectionInterface $targetCollection)
    {
        $this->targetCollection = $targetCollection;
    }

    /**
     * @return \MetaborStd\Statemachine\ProcessInterface
     */
    public function getTargetCollection()
    {
        return $this->targetCollection;
    }

    /**
     * @param string $name
     * @return \MetaborStd\Statemachine\StateInterface
     */
    protected function createState($name)
    {
        return new State($name);
    }

    /**
     * @param StateInterface $sourceState
     * @param TransitionInterface $sourceTransition
     * @throws \InvalidArgumentException
     */
    protected function addTransition(StateInterface $sourceState,
            TransitionInterface $sourceTransition)
    {
        if ($sourceState instanceof State) {
            $sourceState->addTransition($sourceTransition);
        } else {
            throw new \InvalidArgumentException(
                    'Overwrite this method to implement a different type!');
        }
    }

    /**
     * @param TransitionInterface $sourceTransition
     * @throws \InvalidArgumentException
     * @return \Metabor\Statemachine\Transition
     */
    protected function createTransition(TransitionInterface $sourceTransition)
    {
        $targetStateName = $sourceTransition->getTargetState()->getName();
        $targetState = $this->findOrCreateState($targetStateName);
        $eventName = $sourceTransition->getEventName();

        if ($sourceTransition->getConditionName()) {
            if ($sourceTransition instanceof Transition) {
                $condition = $sourceTransition->getCondition();
            } else {
                throw new \InvalidArgumentException(
                        'Overwrite this method to implement a different type!');
            }
        } else {
            $condition = null;
        }

        return new Transition($targetState, $eventName, $condition);
    }

    /**
     * @param object $source
     * @param object $target
     * @throws \RuntimeException
     */
    protected function mergeMetadata($source, $target)
    {
        if ($source instanceof \ArrayAccess) {
            if ($target instanceof \ArrayAccess) {
                if ($source instanceof MetadataInterface) {
                    $metadata = $source->getMetaData();
                    foreach ($metadata as $offset => $value) {
                        $target->offsetSet($offset, $value);
                    }
                } else {
                    throw new \RuntimeException(
                            'Source had to make all metadata available!');
                }
            } else {
                throw new \RuntimeException(
                        'Source metadata can not be merged!');
            }
        }
    }

    /**
     * @param StateInterface $state
     * @throws \InvalidArgumentException
     */
    protected function addState(StateInterface $state)
    {
        if ($this->targetCollection instanceof StateCollection) {
            $this->targetCollection->addState($state);
        } else {
            throw new \InvalidArgumentException(
                    'TargetCollection has to be a StateCollection. Overwrite this method to implement a different type!');
        }
    }

    /**
     * @param string $name
     * @return \MetaborStd\Statemachine\StateInterface
     */
    protected function findOrCreateState($name)
    {
        if ($this->targetCollection->hasState($name)) {
            $targetState = $this->targetCollection->getState($name);
        } else {
            $targetState = $this->createState($name);
            $this->addState($targetState);
        }
        return $targetState;
    }

    /**
     * @param StateInterface $sourceState
     * @throws \InvalidArgumentException
     */
    protected function mergeState(StateInterface $sourceState)
    {
        $name = $sourceState->getName();
        $targetState = $this->findOrCreateState($name);
        $this->mergeMetadata($sourceState, $targetState);
        
        /* @var $transition TransitionInterface*/
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

    /**
     */
    protected function mergeStateCollection(StateCollectionInterface $source)
    {
        /* @var $sourceState StateInterface */
        foreach ($source->getStates() as $sourceState) {
            $this->mergeState($sourceState);
        }
    }

    /**
     * @see \MetaborStd\MergeableInterface::merge()
     */
    public function merge($source)
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
