<?php

namespace Metabor\Statemachine;

use MetaborStd\Event\EventInterface;
use MetaborStd\Statemachine\ConditionInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;
use MetaborStd\WeightedInterface;

/**
 * @author Oliver Tischlinger
 */
class Transition implements TransitionInterface, WeightedInterface
{
    /**
     * @var StateInterface
     */
    private $targetState;

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var ConditionInterface
     */
    private $condition;

    /**
     * @var float
     */
    private $weight = 1;

    /**
     * @param StateInterface     $targetState
     * @param string             $eventName
     * @param ConditionInterface $condition
     */
    public function __construct(StateInterface $targetState, $eventName = null, ConditionInterface $condition = null)
    {
        $this->targetState = $targetState;
        $this->eventName = $eventName;
        $this->condition = $condition;
    }

    /**
     * @see MetaborStd\Statemachine.TransitionInterface::getTargetState()
     */
    public function getTargetState()
    {
        return $this->targetState;
    }

    /**
     * @see MetaborStd\Statemachine.TransitionInterface::getEventName()
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @see MetaborStd\Statemachine.TransitionInterface::getConditionName()
     */
    public function getConditionName()
    {
        if ($this->condition) {
            return $this->condition->getName();
        }
    }

    /**
     * @see MetaborStd\Statemachine.TransitionInterface::isActive()
     */
    public function isActive($subject, \ArrayAccess $context, EventInterface $event = null)
    {
        if ($event) {
            $result = ($event->getName() == $this->eventName);
        } else {
            $result = is_null($this->eventName);
        }
        if ($this->condition) {
            $result = $result && $this->condition->checkCondition($subject, $context);
        }

        return $result;
    }

    /**
     * @return \MetaborStd\Statemachine\ConditionInterface
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
}
