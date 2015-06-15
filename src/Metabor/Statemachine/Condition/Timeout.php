<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\Statemachine\ConditionInterface;
use MetaborStd\Statemachine\LastStateHasChangedDateInterface;

/**
 * @author otischlinger
 */
class Timeout implements ConditionInterface
{
    /**
     * @var string
     */
    protected $timeout;

    /**
     * @param string $timeout
     */
    public function __construct($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Timeout: ' . $this->timeout;
    }

    /**
     * @return \DateInterval
     */
    public function getDateInterval()
    {
        return \DateInterval::createFromDateString($this->timeout);
    }

    /**
     * @param object $subject
     * @param \ArrayAccess $context
     * @return \DateTime
     */
    protected function getLastStateHasChangedDate($subject, \ArrayAccess $context)
    {
        if ($subject instanceof LastStateHasChangedDateInterface) {
            return $subject->getLastStateHasChangedDate();
        } else {
            throw new \InvalidArgumentException('Overwrite this method to implement a different type!');
        }
    }

    /**
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, \ArrayAccess $context)
    {
        // clone date to not change original object
        $date = clone $this->getLastStateHasChangedDate($subject, $context);
        $date->add($this->getDateInterval());

        return ($date <= new \DateTime());
    }
}
