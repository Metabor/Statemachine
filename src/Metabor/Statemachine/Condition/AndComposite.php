<?php

namespace Metabor\Statemachine\Condition;

use Metabor\NamedCollection;
use MetaborStd\Statemachine\ConditionInterface;

class AndComposite implements ConditionInterface
{
    /**
     * @var NamedCollection|ConditionInterface[]
     */
    private $conditions;

    /**
     * @param ConditionInterface $condition
     */
    public function __construct(ConditionInterface $condition)
    {
        $this->conditions = new NamedCollection();
        $this->conditions->add($condition);
    }

    /**
     * @param ConditionInterface $condition
     *
     * @return $this
     */
    public function addAnd(ConditionInterface $condition)
    {
        $this->conditions->add($condition);

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
        return '(' . implode(' and ', $this->conditions->getNames()) . ')';
    }
}
