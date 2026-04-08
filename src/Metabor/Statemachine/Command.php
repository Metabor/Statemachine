<?php

namespace Metabor\Statemachine;

/**
 * @author Oliver Tischlinger
 */
abstract class Command implements \SplObserver
{
    public function __invoke(mixed ...$args): void
    {
        throw new \RuntimeException('Command is not invokable');
    }

    public function update(\SplSubject $subject): void
    {
        if (!method_exists($subject, 'getInvokeArgs')) {
            throw new \RuntimeException('Subject does not support getInvokeArgs');
        }
        $args = $subject->getInvokeArgs();
        $this->__invoke(...$args);
    }

    public function __toString(): string
    {
        return static::class;
    }
}
