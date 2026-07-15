<?php

namespace Metabor\Statemachine\Observer;

use MetaborStd\Statemachine\StatemachineInterface;

/**
 * @author Oliver Tischlinger
 */
class SubjectHasUpdated extends \SplObjectStorage implements \SplObserver
{
    public function update(\SplSubject $subject): void
    {
        if ($subject instanceof StatemachineInterface) {
            $this->offsetSet($subject->getSubject(), null);
        } else {
            $this->offsetSet($subject, null);
        }
    }
}
