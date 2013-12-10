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
     * @var array
     */
    private $eventLayout = array();

    /**
     * @var array
     */
    private $stateLayout = array();

    /**
     * @link http://www.graphviz.org/doc/info/attrs.html
     * 
     * @param string $flag
     * @param scalar $value
     * @param array $layout
     */
    public function setEventLayout($flag, $value, array $layout)
    {
        $value = (string) $value;
        $this->eventLayout[$flag][$value] = $layout;
    }

    /**
     * @link http://www.graphviz.org/doc/info/attrs.html
     * 
     * @param string $flag
     * @param scalar $value
     * @param array $layout
     */
    public function setStateLayout($flag, $value, array $layout)
    {
        $value = (string) $value;
        $this->stateLayout[$flag][$value] = $layout;
    }

    /**
     * @param \ArrayAccess $flagedObject
     * @param array $layout
     * @return array
     */
    protected function getLayoutOptions(\ArrayAccess $flagedObject, array $layout)
    {
        foreach ($layout as $flag => $options) {
            if ($flagedObject->offsetExists($flag)) {
                $value = $flagedObject->offsetGet($flag);
                $value = (string) $value;
                if (isset($options[$value])) {
                    return $options[$value];
                }
            }
        }
        return array();
    }

    /**
     * @param StateInterface $state
     * @return \Fhaculty\Graph\Vertex
     */
    public function createStatusVertex(StateInterface $state)
    {
        $stateName = $state->getName();
        $vertex = $this->createVertex($stateName, true);
        if ($state instanceof \ArrayAccess) {
            $layout = $this->getLayoutOptions($state, $this->stateLayout);
            $vertex->setLayout($layout);
        }
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
            $labelParts[] = 'IF: ' . $conditionName;
        }
        if ($eventName) {
            $event = $state->getEvent($eventName);
            $observers = $event->getObservers();
            $observerName = implode(', ', iterator_to_array($observers, false));
            if ($observerName) {
                $labelParts[] = 'C: ' . $observerName;
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

        $eventName = $transition->getEventName();
        if ($eventName) {
            $event = $state->getEvent($eventName);
            if ($event instanceof \ArrayAccess) {
                $layout = $this->getLayoutOptions($event, $this->eventLayout);
                $edge->setLayout($layout);
            }
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
