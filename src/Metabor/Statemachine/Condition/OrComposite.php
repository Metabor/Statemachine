<?php

namespace Metabor\Statemachine\Condition;

use Metabor\NamedCollection;
use MetaborStd\Statemachine\ConditionInterface;

class OrComposite implements ConditionInterface
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
    public function addOr(ConditionInterface $condition)
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
            if ($condition->checkCondition($subject, $context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @see MetaborStd.NamedInterface::getName()
     */
    public function getName()
    {
        return '(' . implode(' or ', $this->conditions->getNames()) . ')';
    }
}
