<?php

namespace Metabor\Statemachine\Condition;

use MetaborStd\Statemachine\ConditionInterface;
use MetaborStd\Statemachine\LastStateHasChangedDateInterface;

/**
 * @author Oliver Tischlinger
 */
class Timeout implements ConditionInterface
{
    private string $timeout;

    public function __construct(string $timeout)
    {
        $this->timeout = $timeout;
    }

    public function getName(): string
    {
        return 'timeout(' . $this->timeout . ')';
    }

    public function getTimeout(): \DateInterval
    {
        $interval = \DateInterval::createFromDateString($this->timeout);
        if ($interval === false) {
            throw new \InvalidArgumentException('Invalid timeout string: ' . $this->timeout);
        }
        return $interval;
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        if (!$subject instanceof LastStateHasChangedDateInterface) {
            return false;
        }
        $lastChanged = $subject->getLastStateHasChangedDate();
        $now = new \DateTime();
        $elapsed = $now->diff($lastChanged);
        $timeoutInterval = $this->getTimeout();
        $timeoutDate = new \DateTime();
        $timeoutDate->sub($timeoutInterval);
        return $lastChanged <= $timeoutDate;
    }
}
