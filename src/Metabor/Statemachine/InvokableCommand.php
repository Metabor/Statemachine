<?php
namespace Metabor\Statemachine;

use Metabor\Observer\Subject;
use MetaborStd\Event\EventInterface;
use MetaborStd\NamedInterface;

/**
 * @author Oliver Tischlinger
 */
abstract class InvokableCommand extends Subject implements \SplObserver, NamedInterface
{
    /**
     * @param array $args
     * @return mixed|null|void
     */
    abstract protected function __invoke(array $args = null);

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
        $result = $this->__invoke($subject->getInvokeArgs());
        $this->notify();

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
