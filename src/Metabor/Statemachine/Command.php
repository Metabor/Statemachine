<?php
namespace Metabor\Statemachine;

use MetaborStd\Event\EventInterface;

/**
 * @author Oliver Tischlinger
 */
abstract class Command implements \SplObserver
{
    /**
     * @param \SplSubject $subject
     * @throws \InvalidArgumentException
     */
    public function update(\SplSubject $subject)
    {
        if (!$subject instanceof EventInterface) {
            throw new \InvalidArgumentException('Command can only be attached to an event!');
        }
        if (method_exists($this, '__invoke')) {
            call_user_func_array($this, $subject->getInvokeArgs());
        } else {
            throw new \Exception('Command should have at least one __invoke method');
        }
    }

    /**
     * Overwrite this to change the name for the Command that is displayed in the graph.
     *
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}
