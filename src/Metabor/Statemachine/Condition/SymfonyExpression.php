<?php

namespace Metabor\Statemachine\Condition;

use Metabor\Named;
use MetaborStd\Statemachine\ConditionInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * @author Oliver Tischlinger
 */
class SymfonyExpression extends Named implements ConditionInterface
{
    private ExpressionLanguage $expressionLanguage;
    private ?Expression $expression = null;
    private array $values;

    public function __construct(string $name, array $values = [], ?ExpressionLanguage $expressionLanguage = null)
    {
        parent::__construct($name);
        $this->values = $values;
        $this->expressionLanguage = $expressionLanguage ?? new ExpressionLanguage();
    }

    protected function getExpression(): Expression
    {
        if ($this->expression === null) {
            $this->expression = new Expression($this->getName());
        }
        return $this->expression;
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        $values = $this->values;
        $values['subject'] = $subject;
        $values['context'] = $context;
        return (bool) $this->expressionLanguage->evaluate($this->getExpression(), $values);
    }
}
