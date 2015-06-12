<?php
namespace Metabor\Statemachine\Graph;

use Fhaculty\Graph\Graph;
use Metabor\Callback\Callback;
use Metabor\StringConverter;
use MetaborStd\Event\EventInterface;
use MetaborStd\Statemachine\StateCollectionInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;
use MetaborStd\StringConverterInterface;

/**
 * @author otischlinger
 */
class GraphBuilder
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
     * @var \SplObjectStorage
     */
    private $layoutCallback;

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @var StringConverterInterface
     */
    private $stringConverter;

    /**
     * @param Graph $graph
     * @param StringConverterInterface $stringConverter
     */
    public function __construct(Graph $graph, StringConverterInterface $stringConverter = null)
    {
        $this->layoutCallback = new \SplObjectStorage();
        $this->graph = $graph;
        if ($stringConverter) {
            $this->stringConverter = $stringConverter;
        } else {
            $this->stringConverter = new StringConverter();
        }
    }

    /**
     * @link http://www.graphviz.org/doc/info/attrs.html
     *
     * @param string $flag
     * @param scalar $value
     * @param array  $layout
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
     * @param array  $layout
     */
    public function setStateLayout($flag, $value, array $layout)
    {
        $value = (string) $value;
        $this->stateLayout[$flag][$value] = $layout;
    }

    /**
     * @param Callback $callback
     */
    public function attachLayoutCallback(Callback $callback)
    {
        $this->layoutCallback->attach($callback);
    }

    /**
     * @param Callback $callback
     */
    public function detachLayoutCallback(Callback $callback)
    {
        $this->layoutCallback->detach($callback);
    }

    /**
     * @param \ArrayAccess $flaggedObject
     * @param array        $layout
     *
     * @return array
     */
    protected function getLayoutOptions(\ArrayAccess $flaggedObject, array $layout)
    {
        $result = array();
        foreach ($layout as $flag => $options) {
            if ($flaggedObject->offsetExists($flag)) {
                $value = $flaggedObject->offsetGet($flag);
                $value = (string) $value;
                if (isset($options[$value])) {
                    $result += $options[$value];
                }
            }
        }

        /* @var $callback callable */
        foreach ($this->layoutCallback as $callback) {
            $result = $callback($flaggedObject, $result);
        }

        return $result;
    }

    /**
     * @param StateInterface $state
     *
     * @return \Fhaculty\Graph\Vertex
     */
    public function createStatusVertex(StateInterface $state)
    {
        $stateName = $state->getName();
        $vertex = $this->graph->createVertex($stateName, true);
        if ($state instanceof \ArrayAccess) {
            $layout = $this->getLayoutOptions($state, $this->stateLayout);
            if (method_exists($vertex, 'setLayout')) {
                $vertex->setLayout($layout);
            } else {
                foreach ($layout as $name => $value) {
                    $vertex->setAttribute('graphviz.'.$name, $value);
                }
            }
        }

        return $vertex;
    }

    /**
     * @param EventInterface $event
     *
     * @return string
     */
    protected function convertObserverToString(EventInterface $event)
    {
        $observers = array();
        foreach ($event->getObservers() as $observer) {
            $observers[] = $this->stringConverter->convertToString($observer);
        }

        return implode(', ', $observers);
    }

    /**
     * @param TransitionInterface $transition
     *
     * @return string
     */
    protected function getTransitionLabel(StateInterface $state, TransitionInterface $transition)
    {
        $labelParts = array();
        $eventName = $transition->getEventName();
        if ($eventName) {
            $labelParts[] = 'E: '.$eventName;
            $event = $state->getEvent($eventName);
            $observerName = $this->convertObserverToString($event);
            if ($observerName) {
                $labelParts[] = 'C: '.$observerName;
            }
        }
        $conditionName = $transition->getConditionName();
        if ($conditionName) {
            $labelParts[] = 'IF: '.$conditionName;
        }

        $label = implode(PHP_EOL, $labelParts);

        return $label;
    }

    /**
     * @param StateInterface      $state
     * @param TransitionInterface $transition
     */
    protected function addTransition(StateInterface $state, TransitionInterface $transition)
    {
        $sourceStateVertex = $this->createStatusVertex($state);
        $targetStateVertex = $this->createStatusVertex($transition->getTargetState());
        $edge = $sourceStateVertex->createEdgeTo($targetStateVertex);
        $label = $this->getTransitionLabel($state, $transition);
        if ($label) {
            if (method_exists($edge, 'setLayoutAttribute')) {
                $edge->setLayoutAttribute('label', $label);
            } else {
                $edge->setAttribute('graphviz.label', $label);
            }
        }

        $eventName = $transition->getEventName();
        if ($eventName) {
            $event = $state->getEvent($eventName);
            if ($event instanceof \ArrayAccess) {
                $layout = $this->getLayoutOptions($event, $this->eventLayout);
                if (method_exists($edge, 'setLayout')) {
                    $edge->setLayout($layout);
                } else {
                    foreach ($layout as $name => $value) {
                        $edge->setAttribute('graphviz.'.$name, $value);
                    }
                }
            }
        }
    }

    /**
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

    /**
     * @return \Fhaculty\Graph\Graph
     */
    public function getGraph()
    {
        return $this->graph;
    }
}
