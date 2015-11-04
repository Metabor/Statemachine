<?php
namespace Metabor\Statemachine;

use Metabor\Observer\Subject;
use MetaborStd\Event\EventInterface;
use MetaborStd\NamedInterface;

/**
 * @author Oliver Tischlinger
 */
abstract class Command extends Subject implements \SplObserver, NamedInterface
{
    /**
     * @param \SplSubject $subject
     * @return mixed|void
     * @throws \InvalidArgumentException
     */
    public function update(\SplSubject $subject)
    {
        if (!$subject instanceof EventInterface) {
            throw new \InvalidArgumentException('Command can only be attached to an event!');
        }

        /**
         * This is needed in my opinion as it is not obvious how to create a properly working command implementation.
         * I can easily define a command without __invoke method which will do nothing and it has no sense to have
         * empty command implementation so it should be required.
         * As this is an ugly and hacky way to require a method with variable set of parameters I would suggest to have
         * something like Metabor\Statemachine\InvokableCommand
         */
        if (method_exists($this, '__invoke')) {
            $result = call_user_func_array($this, $subject->getInvokeArgs());
            $this->notify();
        }
        else{
            throw new \Exception('Command should have at least one __invoke method');
        }

        return $result;
    }

    /**
     * I would require to provide a name because its really needed for visualisation
     * and the class name might be very long and not very descriptive
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
