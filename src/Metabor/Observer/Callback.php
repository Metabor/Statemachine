<?php

namespace Metabor\Observer;

use MetaborStd\CallbackInterface;

/**
 * @author Oliver Tischlinger
 */
class Callback implements \SplObserver
{
    private CallbackInterface $callback;

    public function __construct(CallbackInterface $callback)
    {
        $this->callback = $callback;
    }

    public function update(\SplSubject $subject): void
    {
        ($this->callback)($subject);
    }
}
