<?php
namespace Metabor\Statemachine;

use Metabor\Event\Event;
use MetaborStd\NamedInterface;
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

/**
 * @author Oliver Tischlinger
 */
class Statemachine extends Subject implements StatemachineInterface
{
    /**
     * @var object
     */
    private $subject;

    /**
     * @var StateInterface
     */
    private $currentState;

    /**
     * @var StateInterface
     */
    private $lastState;

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
     * @var ProcessInterface
     */
    private $process;

    /**
     * @var \SplObjectStorage
     */
    private $commandExecutionObservers;

    /**
     * @var \SplObjectStorage
     */
    private $stateChangeObservers;

    /**
     * @var \SplObjectStorage
     */
    private $eventExecutionObservers;


    /**
     * @param object $subject
     * @param ProcessInterface $process
     * @param string $stateName
     */
    public function __construct(
        $subject,
        ProcessInterface $process,
        $stateName = null,
        TransitionSelectorInterface $transitonSelector = null
    ) {
        parent::__construct();
        $this->subject = $subject;
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
        $this->process = $process;

        $this->commandExecutionObservers = new \SplObjectStorage();
        $this->stateChangeObservers = new \SplObjectStorage();
        $this->eventExecutionObservers = new \SplObjectStorage();
    }

    /**
     * @return ProcessInterface
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->process->getName();
    }

    /**
     * @see MetaborStd\Statemachine.StatemachineInterface::getCurrentState()
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * @return StateInterface
     */
    public function getLastState()
    {
        return $this->lastState;
    }

    /**
     * @param \ArrayAccess $context
     * @param EventInterface $event
     */
    protected function doCheckTransitions(\ArrayAccess $context, EventInterface $event = null)
    {
        try {
            $transitions = $this->currentState->getTransitions();
            $activeTransitions = new ActiveTransitionFilter($transitions, $this->getSubject(), $context, $event);
            $this->selectedTransition = $this->transitonSelector->selectTransition($activeTransitions);
            if ($this->selectedTransition) {
                $targetState = $this->selectedTransition->getTargetState();
                if ($this->currentState != $targetState) {
                    $this->lastState = $this->currentState;
                    $this->currentState = $targetState;
                    $this->notify();
                    $this->notifyObservers($this->stateChangeObservers);
                    $this->selectedTransition = null;
                    $this->lastState = null;
                }
                $this->checkTransitions();
            }
        } catch (\Exception $exception) {
            $message = 'Exception was thrown when doing a transition from current state "' . $this->currentState->getName() . '"';
            if ($this->currentEvent instanceof NamedInterface) {
                $message .= ' with event "' . $this->currentEvent->getName() . '"';
            }
            throw new \RuntimeException($message, 0, $exception);
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
     * is called after dispatcher was executed.
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
     * @param DispatcherInterface $dispatcher
     * @param string $name
     * @param \ArrayAccess $context
     *
     * @throws \RuntimeException
     */
    public function dispatchEvent(DispatcherInterface $dispatcher, $name, \ArrayAccess $context = null)
    {
        if ($this->dispatcher) {
            throw new \RuntimeException('Event dispatching is still running!');
        }

        if ($this->currentState->hasEvent($name)) {
            $this->dispatcher = $dispatcher;

            if ($context) {
                $this->currentContext = $context;
            } else {
                $this->currentContext = new \ArrayIterator(array());
            }
            $this->currentEvent = $this->currentState->getEvent($name);
            $this->registerCommandObservers($this->currentEvent);
            $dispatcher->dispatch($this->currentEvent, array($this->subject, $this->currentContext),
                new Callback(array($this, 'onDispatcherReady')));
        } else {
            throw new \WrongEventForStateException($this->currentState->getName(), $name);
        }

    }

    /**
     * @see MetaborStd\Statemachine.StatemachineInterface::triggerEvent()
     */
    public function triggerEvent($name, \ArrayAccess $context = null)
    {
        $dispatcher = new Dispatcher();
        $this->dispatchEvent($dispatcher, $name, $context);
        $dispatcher();
        $this->notifyObservers($this->eventExecutionObservers);
    }

    /**
     * @see MetaborStd\Statemachine.StatemachineInterface::checkTransitions()
     */
    public function checkTransitions()
    {
        $context = new \ArrayIterator(array());
        $this->doCheckTransitions($context);
    }

    /**
     * @see \MetaborStd\Statemachine\StatemachineInterface::getSubject()
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param \SplObserver $observer
     */
    public function attachStateChangeObserver(\SplObserver $observer)
    {
        $this->stateChangeObservers->attach($observer);
    }

    /**
     * @param \SplObserver $observer
     */
    public function attachEventExecutionObserver(\SplObserver $observer)
    {
        $this->eventExecutionObservers->attach($observer);
    }

    /**
     * @param \SplObserver $observer
     */
    public function attachCommandExecutionObserver(\SplObserver $observer)
    {
        $this->commandExecutionObservers->attach($observer);
    }

    /**
     * @param \SplObjectStorage $observerStorage
     */
    private function notifyObservers(\SplObjectStorage $observerStorage)
    {
        foreach ($observerStorage as $observer){
            $observer->update($this);
        }
    }

    /**
     * @param EventInterface $event
     */
    private function registerCommandObservers($event)
    {
        if(!$event instanceof Event || !$this->commandExecutionObservers->count()){
            return;
        }
        foreach ($event->getObservers() as $eventObservers)
        {
            if($eventObservers instanceof InvokableCommand)
            {
                foreach ($this->commandExecutionObservers as $commandObserver){
                    $eventObservers->attach($commandObserver);
                }
            }
        }
    }
}
