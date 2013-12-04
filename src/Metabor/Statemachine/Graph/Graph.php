<?php
namespace Metabor\Statemachine\Graph;
use Metabor\Statemachine\Command;

use MetaborStd\Statemachine\TransitionInterface;

use Fhaculty\Graph\Vertex;

use MetaborStd\Statemachine\StateInterface;

use MetaborStd\Statemachine\StateCollectionInterface;

use Fhaculty\Graph\Graph as GraphLib;

/**
 * @author otischlinger
 *
 */
class Graph extends GraphLib
{
    /**
     * @param StateInterface $state
     * @return \Fhaculty\Graph\Vertex
     */
    public function createStatusVertex(StateInterface $state)
    {
        $stateName = $state->getName();
        $vertex = $this->createVertex($stateName, true);
        return $vertex;
    }

    /**
     * @param TransitionInterface $transition
     * @return string
     */
    protected function getTransitionLabel(StateInterface $state, TransitionInterface $transition)
    {
        $labelParts = array();
        $eventName = $transition->getEventName();
        if ($eventName) {
            $labelParts[] = 'E: ' . $eventName;
        }
        $conditionName = $transition->getConditionName();
        if ($conditionName) {
            $labelParts[] = 'C: ' . $conditionName;
        }
        if ($eventName) {
            $event = $state->getEvent($eventName);
            $observers = $event->getObservers();
            $observerName = implode(', ', iterator_to_array($observers, false));
            if ($observerName) {
                $labelParts[] = 'O: ' . $observerName;
            }
        }

        $label = implode(PHP_EOL, $labelParts);

        return $label;
    }

    /**
     * 
     * @param StateInterface $state
     * @param TransitionInterface $transition
     */
    protected function addTransition(StateInterface $state, TransitionInterface $transition)
    {
        $sourceStateVertex = $this->createStatusVertex($state);
        $targetStateVertex = $this->createStatusVertex($transition->getTargetState());
        $edge = $sourceStateVertex->createEdgeTo($targetStateVertex);
        $label = $this->getTransitionLabel($state, $transition);
        if ($label) {
            $edge->setLayoutAttribute('label', $label);
        }
    }

    /**
     * 
     * @param StateInterface $state
     */
    public function addState(StateInterface $state)
    {
        $this->createStatusVertex($state);
        /* @var $transition TransitionInterface */
        foreach ($state->getTransitions() as $transition) {
            $this->addTransition($state, $transition);
        }
    }

    /**
     * @param \Traversable $states
     */
    public function addStates(\Traversable $states)
    {
        foreach ($states as $state) {
            $this->addState($state);
        }

    }

    /**
     * @param StateCollectionInterface $stateCollection
     */
    public function addStateCollection(StateCollectionInterface $stateCollection)
    {
        $this->addStates($stateCollection->getStates());
    }

}
