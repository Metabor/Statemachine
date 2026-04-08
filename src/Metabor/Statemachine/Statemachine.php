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
    private object $subject;
    private StateInterface $currentState;
    private ?StateInterface $lastState = null;
    private ?DispatcherInterface $dispatcher = null;
    private ?EventInterface $currentEvent = null;
    private ?\ArrayAccess $currentContext = null;
    private TransitionSelectorInterface $transitonSelector;
    private ?TransitionInterface $selectedTransition = null;
    private ProcessInterface $process;
    private MutexInterface $mutex;
    private bool $autoreleaseLock = true;

    public function __construct(
        object $subject,
        ProcessInterface $process,
        ?string $stateName = null,
        ?TransitionSelectorInterface $transitonSelector = null,
        ?MutexInterface $mutex = null
    ) {
        parent::__construct();
        $this->subject = $subject;
        if ($stateName) {
            $this->currentState = $process->getState($stateName);
        } else {
            $this->currentState = $process->getInitialState();
        }
        $this->transitonSelector = $transitonSelector ?? new OneOrNoneActiveTransition();
        $this->process = $process;
        $this->mutex = $mutex ?? new NullMutex();
    }

    public function getProcess(): ProcessInterface
    {
        return $this->process;
    }

    public function getCurrentState(): StateInterface
    {
        return $this->currentState;
    }

    public function getLastState(): ?StateInterface
    {
        return $this->lastState;
    }

    protected function doCheckTransitions(\ArrayAccess $context, ?EventInterface $event = null): void
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
                $this->doCheckTransitions($context);
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

    public function getSelectedTransition(): ?TransitionInterface
    {
        return $this->selectedTransition;
    }

    /**
     * Is called after dispatcher was executed.
     */
    public function onDispatcherReady(): void
    {
        if ($this->dispatcher && $this->dispatcher->isReady()) {
            $context = $this->currentContext;
            $event = $this->currentEvent;
            $this->dispatcher = null;
            $this->currentContext = null;
            $this->currentEvent = null;
            $this->doCheckTransitions($context, $event);
            if ($this->autoreleaseLock) {
                $this->releaseLock();
            }
        }
    }

    public function dispatchEvent(DispatcherInterface $dispatcher, string $name, ?\ArrayAccess $context = null): void
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
                    $this->currentContext = new \ArrayIterator([]);
                }
                $this->currentEvent = $this->currentState->getEvent($name);

                $dispatcher->dispatch($this->currentEvent, [$this->subject, $this->currentContext], new Callback([$this, 'onDispatcherReady']));
            } else {
                throw new WrongEventForStateException($this->currentState->getName(), $name);
            }
        }
    }

    public function triggerEvent(string $name, ?\ArrayAccess $context = null): void
    {
        $dispatcher = new Dispatcher();
        $this->dispatchEvent($dispatcher, $name, $context);
        $dispatcher();
    }

    public function checkTransitions(?\ArrayAccess $context = null): void
    {
        $this->acquireLockOrThrowException();
        if (!$context) {
            $context = new \ArrayIterator([]);
        }
        $this->doCheckTransitions($context);
        if ($this->autoreleaseLock) {
            $this->releaseLock();
        }
    }

    public function getSubject(): object
    {
        return $this->subject;
    }

    public function getCurrentContext(): ?\ArrayAccess
    {
        return $this->currentContext;
    }

    protected function acquireLockOrThrowException(): void
    {
        if (!$this->acquireLock()) {
            throw new LockCanNotBeAcquiredException('Lock can not be acquired!');
        }
    }

    public function isAutoreleaseLock(): bool
    {
        return $this->autoreleaseLock;
    }

    public function setAutoreleaseLock(bool $autoreleaseLock): void
    {
        $this->autoreleaseLock = $autoreleaseLock;
    }

    /**
     * Use this function if you want to acquire lock before calling triggerEvent or checkTransitions.
     * Lock is acquired automatically when calling dispatchEvent or checkTransitions.
     */
    public function acquireLock(): bool
    {
        if ($this->mutex->isAcquired()) {
            return true;
        } else {
            return $this->mutex->acquireLock();
        }
    }

    /**
     * Manual release of the lock.
     * Can be used for example if you disable autorelease of locks.
     */
    public function releaseLock(): void
    {
        $this->mutex->releaseLock();
    }

    public function isLockAcquired(): bool
    {
        return $this->mutex->isAcquired();
    }
}
