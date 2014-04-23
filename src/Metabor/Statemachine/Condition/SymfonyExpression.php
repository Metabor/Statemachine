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
     * @var array
     */
    private $values;

    /**
     * 
     * @param string $name
     * @param array $values
     * @param ExpressionLanguage $expressionLanguage
     */
    public function __construct($name, array $values = array(), ExpressionLanguage $expressionLanguage = null)
    {
        parent::__construct($name);
        $this->values = $values;
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
            $keys = array_keys($this->values);
            $keys[] = 'subject';
            $keys[] = 'context';
            $this->expression = $this->expressionLanguage->parse($this->getName(), $keys);
        }
        return $this->expression;
    }

    /**
     *
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, ArrayAccess $context)
    {
        $values = $this->values;
        $values['subject'] = $subject;
        $values['context'] = $context;
        return $this->expressionLanguage->evaluate($this->getExpression(), $values);
    }
}
