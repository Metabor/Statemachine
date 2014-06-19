<?php
namespace Metabor\Event;

use MetaborStd\CallbackInterface;
use MetaborStd\Event\EventInterface;
use MetaborStd\Event\DispatcherInterface;
use RuntimeException;

/**
 *
 * @author Oliver Tischlinger
 *
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var array
     */
    private $commands = array();

    /**
     * @var array
     */
    private $onReadyCallbacks = array();

    /**
     * @var boolean
     */
    private $isReady = false;

    /**
     * @param callable $command
     * @param array $arguments
     */
    protected function addCommand($command, array $arguments)
    {
        $this->commands[] = func_get_args();
    }

    /**
     * @return array
     */
    protected function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param callable $command
     * @param array $arguments
     */
    protected function removeCommand($command, array $arguments)
    {
        $key = array_search(func_get_args(), $this->commands);
        if ($key !== false) {
            unset($this->commands[$key]);
        }
    }

    /**
     * @param EventInterface $event
     * @param array $arguments
     */
    protected function addEvent(EventInterface $event, array $arguments)
    {
        $this->addCommand($event, $arguments);
    }

    /**
     * @see \MetaborStd\Event\DispatcherInterface::dispatch()
     */
    public function dispatch(EventInterface $event, array $arguments = array(), CallbackInterface $onReadyCallback = null)
    {
        if (!$this->isReady) {
            $this->addEvent($event, $arguments);
            if ($onReadyCallback) {
                $this->onReadyCallbacks[] = $onReadyCallback;
            }
        } else {
            throw new RuntimeException('Was already invoked!');
        }
    }

    /**
     * @see \MetaborStd\CallbackInterface::__invoke()
     */
    public function __invoke()
    {
        if ($this->isReady) {
            throw new RuntimeException('Was already invoked!');
        } else {
            foreach ($this->getCommands() as $list) {
                list($command, $arguments) = $list;
                call_user_func_array($command, $arguments);
            }
            $this->isReady = true;
            foreach ($this->onReadyCallbacks as $onReadyCallback) {
                $onReadyCallback();
            }
        }
    }

    /**
     * @return boolean
     */
    public function isReady()
    {
        return $this->isReady;
    }

}
