<?php

namespace Metabor\Event;

use MetaborStd\CallbackInterface;
use MetaborStd\Event\DispatcherInterface;
use MetaborStd\Event\EventInterface;

/**
 * @author Oliver Tischlinger
 */
class Dispatcher implements DispatcherInterface
{
    private array $commands = [];
    private array $onReadyCallbacks = [];
    private bool $isReady = false;

    protected function addCommand(callable $command, array $arguments): void
    {
        $this->commands[] = [$command, $arguments];
    }

    protected function getCommands(): array
    {
        return $this->commands;
    }

    protected function removeCommand(callable $command, array $arguments): void
    {
        $key = array_search([$command, $arguments], $this->commands);
        if ($key !== false) {
            unset($this->commands[$key]);
        }
    }

    protected function addEvent(EventInterface $event, array $arguments): void
    {
        $this->addCommand($event, $arguments);
    }

    public function dispatch(EventInterface $event, array $arguments = [], ?CallbackInterface $onReadyCallback = null): void
    {
        if (!$this->isReady) {
            $this->addEvent($event, $arguments);
            if ($onReadyCallback) {
                $this->onReadyCallbacks[] = $onReadyCallback;
            }
        } else {
            throw new \RuntimeException('Was already invoked!');
        }
    }

    public function __invoke(mixed ...$args): mixed
    {
        if ($this->isReady) {
            throw new \RuntimeException('Was already invoked!');
        } else {
            foreach ($this->getCommands() as $list) {
                [$command, $arguments] = $list;
                $command(...$arguments);
            }
            $this->isReady = true;
            foreach ($this->onReadyCallbacks as $onReadyCallback) {
                $onReadyCallback();
            }
        }
        return null;
    }

    public function isReady(): bool
    {
        return $this->isReady;
    }
}
