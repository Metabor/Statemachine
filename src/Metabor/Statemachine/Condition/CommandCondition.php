<?php
namespace Foodpanda\Workflow\Condition;

use Metabor\Statemachine\InvokableCommand;
use MetaborStd\Statemachine\ConditionInterface;

class CommandCondition implements ConditionInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ConditionInterface
     */
    private $conditionToCheck;

    /**
     * @var InvokableCommand
     */
    private $trueCommand;

    /**
     * @var InvokableCommand
     */
    private $falseCommand;

    /**
     * @param string $name
     * @param ConditionInterface $conditionToCheck
     * @param InvokableCommand $trueCommand
     * @param InvokableCommand $falseCommand
     */
    public function __construct($name, $conditionToCheck, $trueCommand, $falseCommand = null)
    {
        $this->name = $name;
        $this->conditionToCheck = $conditionToCheck;
        $this->trueCommand = $trueCommand;
        $this->falseCommand = $falseCommand;
    }


    /**
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     * @param object $subject
     * @param \ArrayAccess $context
     * @return bool
     */
    public function checkCondition($subject, \ArrayAccess $context)
    {
        $commandParams = ['subject' => $subject, 'context' => $context];
        if ($this->conditionToCheck->checkCondition($subject, $context)) {
            $this->trueCommand->__invoke($commandParams);
            return true;
        } elseif ($this->falseCommand) {
            $this->trueCommand->__invoke($commandParams);
        }
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $commandNames = ' -> C: ' . $this->trueCommand->getName();
        if ($this->falseCommand) {
            $commandNames .= ' else -> C: ' . $this->falseCommand->getName();
        }

        return $this->name . $commandNames;
    }
}
