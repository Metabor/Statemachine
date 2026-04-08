<?php

namespace Metabor\Statemachine\Condition;

use Metabor\NamedCollection;
use MetaborStd\Statemachine\ConditionInterface;

/**
 * @author Oliver Tischlinger
 */
class AndComposite implements ConditionInterface
{
    private NamedCollection $conditions;

    public function __construct(?ConditionInterface $condition = null)
    {
        $this->conditions = new NamedCollection();
        if ($condition) {
            $this->conditions->add($condition);
        }
    }

    public function addAnd(ConditionInterface $condition): self
    {
        $this->conditions->add($condition);
        return $this;
    }

    public function addCondition(ConditionInterface $condition): self
    {
        return $this->addAnd($condition);
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->checkCondition($subject, $context)) {
                return false;
            }
        }
        return true;
    }

    public function getName(): string
    {
        return 'and(' . implode(', ', $this->conditions->getNames()) . ')';
    }
}
