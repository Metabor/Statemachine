<?php
namespace Metabor\Statemachine\Condition;

use Metabor\Named;
use MetaborStd\Statemachine\ConditionInterface;
use ArrayAccess;
use InvalidArgumentException;

/**
 * @author otischlinger
 *
 */
class Callback extends Named implements ConditionInterface
{
    /**
     *
     * @var callable
     */
    private $callable;

    /**
     *
     * @param string $name
     * @param callable $callable
     */
    public function __construct($name, $callable)
    {
        parent::__construct($name);
        if (!is_callable($callable)) {
            throw new InvalidArgumentException('Argument is not callable!');
        }
        $this->callable = $callable;
    }

    /**
     *
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, ArrayAccess $context)
    {
        return call_user_func($this->callable, $subject, $context);
    }

}
