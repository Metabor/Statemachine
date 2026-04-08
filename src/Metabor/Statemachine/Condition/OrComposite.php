<?php

namespace Metabor\Statemachine\Condition;

use Metabor\NamedCollection;
use MetaborStd\Statemachine\ConditionInterface;

/**
 * @author Oliver Tischlinger
 */
class OrComposite implements ConditionInterface
{
    private NamedCollection $conditions;

    public function __construct(?ConditionInterface $condition = null)
    {
        $this->conditions = new NamedCollection();
        if ($condition) {
            $this->conditions->add($condition);
        }
    }

    public function addOr(ConditionInterface $condition): self
    {
        $this->conditions->add($condition);
        return $this;
    }

    public function addCondition(ConditionInterface $condition): self
    {
        $this->conditions->add($condition);
        return $this;
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        foreach ($this->conditions as $condition) {
            if ($condition->checkCondition($subject, $context)) {
                return true;
            }
        }
        return false;
    }

    public function getName(): string
    {
        return 'or(' . implode(', ', $this->conditions->getNames()) . ')';
    }
}
