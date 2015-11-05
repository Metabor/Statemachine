<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\Statemachine\ConditionInterface;

class AndCompositeCondition implements ConditionInterface
{
    /**
     * @var ConditionInterface[]
     */
    private $conditions = [];

    /**
     * @var array
     */
    private $conditionNames = [];

    /**
     * @param ConditionInterface $condition
     */
    public function __construct(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
        $this->conditionNames[] = $condition->getName();
    }

    /**
     * @param ConditionInterface $condition
     * @return $this
     */
    public function addAnd(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
        $this->conditionNames[] = $condition->getName();
        return $this;
    }

    /**
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, \ArrayAccess $context)
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->checkCondition($subject, $context)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @see MetaborStd.NamedInterface::getName()
     */
    public function getName()
    {
        return implode(' and ', $this->conditionNames);
    }
}
