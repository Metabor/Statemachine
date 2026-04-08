<?php

namespace Metabor\Statemachine;

use Metabor\NamedCollection;
use Metabor\Statemachine\Util\StateCollectionMerger;
use MetaborStd\MergeableInterface;
use MetaborStd\Statemachine\StateCollectionInterface;
use MetaborStd\Statemachine\StateInterface;

/**
 * @author Oliver Tischlinger
 */
class StateCollection implements StateCollectionInterface, MergeableInterface
{
    private NamedCollection $states;
    private ?StateCollectionMerger $stateCollectionMerger = null;

    public function __construct()
    {
        $this->states = new NamedCollection();
    }

    public function getState(string $name): StateInterface
    {
        return $this->states->get($name);
    }

    public function getStates(): \Traversable
    {
        return $this->states->getIterator();
    }

    public function hasState(string $name): bool
    {
        return $this->states->has($name);
    }

    public function addState(StateInterface $state): void
    {
        $this->states->add($state);
    }

    public function getStateCollectionMerger(): StateCollectionMerger
    {
        if (!$this->stateCollectionMerger) {
            $this->stateCollectionMerger = new StateCollectionMerger($this);
        }

        return $this->stateCollectionMerger;
    }

    public function merge(mixed $source): void
    {
        $merger = $this->getStateCollectionMerger();
        $merger->merge($source);
    }
}
