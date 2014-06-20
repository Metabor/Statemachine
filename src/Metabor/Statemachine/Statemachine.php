<?php
namespace Metabor\Statemachine;
use Metabor\Statemachine\Factory\TransitionSelector\OneOrNoneActiveTransition;
use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use Metabor\Statemachine\Transition\ActiveTransitionFilter;
use Metabor\Callback\Callback;
use Metabor\Event\Dispatcher;
use MetaborStd\Event\DispatcherInterface;
use Metabor\Observer\Subject;
use MetaborStd\Event\EventInterface;
use MetaborStd\Statemachine\StatemachineInterface;
use MetaborStd\Statemachine\ProcessInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;
use ArrayAccess;
use ArrayIterator;
use RuntimeException;

/**
 *
 * @author Oliver Tischlinger
 *
 */
class Statemachine extends Subject implements StatemachineInterface
{

    /**
     *
     * @var ProcessInterface
     */
    private $process;

    /**
     *
     * @var object
     */
    private $subject;

    /**
     *
     * @var StateInterface
     */
    private $currentState;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    /**
     * @var EventInterface
     */
    private $currentEvent;

    /**
     * @var \ArrayAccess
     */
    private $currentContext;

    /**
     * @var TransitionSelectorInterface
     */
    private $transitonSelector;

    /**
     * @var TransitionInterface
     */
    private $selectedTransition;

    /**
     *
     * @param object           $subject
     * @param ProcessInterface $process
     * @param string           $stateName
     */
    public function __construct($subject, ProcessInterface $process, $stateName = null, TransitionSelectorInterface $transitonSelector = null)
    {
        parent::__construct();
        $this->subject = $subject;
        $this->process = $process;
        if ($stateName) {
            $this->currentState = $process->getState($stateName);
        } else {
            $this->currentState = $process->getInitialState();
        }
        if ($transitonSelector) {
            $this->transitonSelector = $transitonSelector;
        } else {
            $this->transitonSelector = new OneOrNoneActiveTransition();
        }
    }

    /**
     *
     * @see MetaborStd\Statemachine.StatemachineInterface::getCurrentState()
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     *
     * @param ArrayAccess    $context
     * @param EventInterface $event
     */
    protected function doCheckTransitions(ArrayAccess $context, EventInterface $event = null)
    {
        $transitions = $this->currentState->getTransitions();
        $activeTransitions = new ActiveTransitionFilter($transitions, $this->getSubject(), $context, $event);
        $this->selectedTransition = $this->transitonSelector->selectTransition($activeTransitions);
        if ($this->selectedTransition) {
            $this->currentState = $this->selectedTransition->getTargetState();
            $this->notify();
            $this->selectedTransition = null;
            $this->checkTransitions();
        }
    }

    /**
     * @return \MetaborStd\Statemachine\TransitionInterface
     */
    public function getSelectedTransition()
    {
        return $this->selectedTransition;
    }

    /**
     * is called after dispatcher was executed
     */
    public function onDispatcherReady()
    {
        if ($this->dispatcher && $this->dispatcher->isReady()) {
            $context = $this->currentContext;
            $event = $this->currentEvent;
            $this->dispatcher = null;
            $this->currentContext = null;
            $this->currentEvent = null;
            $this->doCheckTransitions($context, $event);
        }
    }

    /**
     * @param  DispatcherInterface $dispatcher
     * @param  string              $name
     * @param  ArrayAccess         $context
     * @throws RuntimeException
     */
    public function dispatchEvent(DispatcherInterface $dispatcher, $name, ArrayAccess $context = null)
    {
        if ($this->dispatcher) {
            throw new RuntimeException('Event dispatching is still running!');
        } else {
            if ($this->currentState->hasEvent($name)) {
                $this->dispatcher = $dispatcher;

                if ($context) {
                    $this->currentContext = $context;
                } else {
                    $this->currentContext = new ArrayIterator(array());
                }
                $this->currentEvent = $this->currentState->getEvent($name);

                $dispatcher->dispatch($this->currentEvent, array($this->subject, $this->currentContext), new Callback(array($this, 'onDispatcherReady')));
            } else {
                throw new RuntimeException('Current State did not have event "' . $name . '"');
            }
        }
    }

    /**
     *
     * @see MetaborStd\Statemachine.StatemachineInterface::triggerEvent()
     */
    public function triggerEvent($name, ArrayAccess $context = null)
    {
        $dispatcher = new Dispatcher();
        $this->dispatchEvent($dispatcher, $name, $context);
        $dispatcher();
    }

    /**
     *
     * @see MetaborStd\Statemachine.StatemachineInterface::checkTransitions()
     */
    public function checkTransitions()
    {
        $context = new ArrayIterator(array());
        $this->doCheckTransitions($context);
    }

    /**
     *
     * @see \MetaborStd\Statemachine\StatemachineInterface::getSubject()
     */
    public function getSubject()
    {
        return $this->subject;
    }

}
