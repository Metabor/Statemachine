<?php

namespace Metabor\Statemachine;

use Metabor\Callback\Callback;
use Metabor\Event\Dispatcher;
use Metabor\Observer\Subject;
use Metabor\Semaphore\NullMutex;
use Metabor\Statemachine\Exception\LockCanNotBeAcquiredException;
use Metabor\Statemachine\Exception\WrongEventForStateException;
use Metabor\Statemachine\Factory\TransitionSelector\OneOrNoneActiveTransition;
use Metabor\Statemachine\Transition\ActiveTransitionFilter;
use MetaborStd\Event\DispatcherInterface;
use MetaborStd\Event\EventInterface;
use MetaborStd\NamedInterface;
use MetaborStd\Semaphore\MutexInterface;
use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use MetaborStd\Statemachine\ProcessInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\StatemachineInterface;
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
     * @var MutexInterface
     */
    private $mutex;

    /**
     * @param object                      $subject
     * @param ProcessInterface            $process
     * @param string                      $stateName
     * @param TransitionSelectorInterface $transitonSelector
     * @param MutexInterface              $mutex
     */
    public function __construct(
        $subject,
        ProcessInterface $process,
        $stateName = null,
        TransitionSelectorInterface $transitonSelector = null,
        MutexInterface $mutex = null
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
        if ($mutex) {
            $this->mutex = $mutex;
        } else {
            $this->mutex = new NullMutex();
        }
    }

    /**
     * @return ProcessInterface
     */
    public function getProcess()
    {
        return $this->process;
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
     * @param \ArrayAccess   $context
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
                    $this->currentContext = $context;
                    $this->currentEvent = $event;
                    $this->notify();
                    $this->currentContext = null;
                    $this->currentEvent = null;
                    $this->selectedTransition = null;
                    $this->lastState = null;
                }
                $this->checkTransitions($context);
            }
        } catch (\Exception $exception) {
            $message = 'Exception was thrown when doing a transition from current state "' . $this->currentState->getName() . '"';
            if ($this->currentEvent instanceof NamedInterface) {
                $message .= ' with event "' . $this->currentEvent->getName() . '"';
            }
            if ($this->subject instanceof NamedInterface) {
                $message .= ' for "' . $this->subject->getName() . '"';
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
            $this->mutex->releaseLock();
        }
    }

    /**
     * @param DispatcherInterface $dispatcher
     * @param string              $name
     * @param \ArrayAccess        $context
     *
     * @throws \RuntimeException
     */
    public function dispatchEvent(DispatcherInterface $dispatcher, $name, \ArrayAccess $context = null)
    {
        if ($this->dispatcher) {
            throw new \RuntimeException('Event dispatching is still running!');
        } else {
            if ($this->currentState->hasEvent($name)) {
                $this->acquireLockOrThrowException();
                $this->dispatcher = $dispatcher;

                if ($context) {
                    $this->currentContext = $context;
                } else {
                    $this->currentContext = new \ArrayIterator(array());
                }
                $this->currentEvent = $this->currentState->getEvent($name);

                $dispatcher->dispatch($this->currentEvent, array($this->subject, $this->currentContext), new Callback(array($this, 'onDispatcherReady')));
            } else {
                throw new WrongEventForStateException($this->currentState->getName(), $name);
            }
        }
    }

    /**
     * @param string $name
     * @param \ArrayAccess|null $context
     */
    public function triggerEvent($name, \ArrayAccess $context = null)
    {
        $dispatcher = new Dispatcher();
        $this->dispatchEvent($dispatcher, $name, $context);
        $dispatcher();
    }

    /**
     * @param \ArrayAccess|null $context
     */
    public function checkTransitions(\ArrayAccess $context = null)
    {
        $this->acquireLockOrThrowException();
        if (!$context) {
            $context = new \ArrayIterator(array());
        }
        $this->doCheckTransitions($context);
        $this->mutex->releaseLock();
    }

    /**
     * @see \MetaborStd\Statemachine\StatemachineInterface::getSubject()
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return \ArrayAccess
     */
    public function getCurrentContext()
    {
        return $this->currentContext;
    }

    /**
     * @throws LockCanNotBeAcquiredException
     */
    protected function acquireLockOrThrowException()
    {
        if (!$this->acquireLock()) {
            throw new LockCanNotBeAcquiredException('Lock can not be acquired!');
        }
    }

    /**
     * Use this function if you want to aquire lock before calling triggerEvent or checkTransitions.
     * Lock is aquired automatically when calling dispatchEvent or checkTransitions.
     *
     * @return bool
     */
    public function acquireLock()
    {
        if ($this->mutex->isAcquired()) {
            return true;
        } else {
            return $this->mutex->acquireLock();
        }
    }
}
