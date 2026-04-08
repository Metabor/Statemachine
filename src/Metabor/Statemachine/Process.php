<?php

namespace Metabor\Statemachine;

use Metabor\Named;
use Metabor\Statemachine\Util\SetupHelper;
use Metabor\Statemachine\Util\StateCollectionMerger;
use MetaborStd\MergeableInterface;
use MetaborStd\Statemachine\ProcessInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class Process extends Named implements ProcessInterface, MergeableInterface
{
    private StateCollection $states;
    private StateInterface $initialState;
    private ?StateCollectionMerger $stateCollectionMerger = null;

    public function __construct(string $name, StateInterface $initialState)
    {
        parent::__construct($name);
        $this->initialState = $initialState;
        $this->createCollection();
        $this->initProcess(new SetupHelper($this->states));
    }

    protected function addState(StateInterface $state): void
    {
        $name = $state->getName();
        if ($this->states->hasState($name)) {
            if ($this->states->getState($name) !== $state) {
                throw new \Exception('There is already a different state with name "' . $name . '"');
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

    protected function initProcess(SetupHelper $setupHelper): void
    {
        // overwrite to use SetupHelper
    }

    protected function createCollection(): void
    {
        $this->states = new StateCollection();
        $this->addState($this->initialState);
    }

    public function getInitialState(): StateInterface
    {
        return $this->initialState;
    }

    public function getStates(): \Traversable
    {
        return $this->states->getStates();
    }

    public function getState(string $name): StateInterface
    {
        return $this->states->getState($name);
    }

    public function hasState(string $name): bool
    {
        return $this->states->hasState($name);
    }

    public function getStateCollectionMerger(): StateCollectionMerger
    {
        if (!$this->stateCollectionMerger) {
            $this->stateCollectionMerger = new StateCollectionMerger($this->states);
        }

        return $this->stateCollectionMerger;
    }

    public function merge(mixed $source): void
    {
        $merger = $this->getStateCollectionMerger();
        $merger->merge($source);
    }
}
