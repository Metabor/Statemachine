<?php
namespace Metabor\Statemachine\Condition;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Expression;
use Metabor\Named;
use MetaborStd\Statemachine\ConditionInterface;
use ArrayAccess;
use InvalidArgumentException;

/**
 * @author otischlinger
 *
 */
class SymfonyExpression extends Named implements ConditionInterface
{
    /**
     *
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    /**
     * @var Expression
     */
    private $expression;

    /**
     * 
     * @param string $name
     * @param ExpressionLanguage $expressionLanguage
     */
    public function __construct($name, ExpressionLanguage $expressionLanguage = null)
    {
        parent::__construct($name);
        if ($expressionLanguage) {
            $this->expressionLanguage = $expressionLanguage;
        } else {
            $this->expressionLanguage = new ExpressionLanguage();
        }
    }

    /**
     * @return \Symfony\Component\ExpressionLanguage\Expression
     */
    protected function getExpression()
    {
        if (!$this->expression) {
            $this->expression = $this->expressionLanguage->parse($this->getConditionName(), array('subject', 'context'));
        }
        return $this->expression;
    }

    /**
     *
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, ArrayAccess $context)
    {
        return $this->expressionLanguage->evaluate($this->getExpression(), array('subject' => $subject, 'context' => $context));
    }
}
