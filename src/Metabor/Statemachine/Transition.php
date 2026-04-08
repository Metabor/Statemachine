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
    private StateInterface $targetState;
    private ?string $eventName;
    private ?ConditionInterface $condition;
    private float $weight = 1;

    public function __construct(StateInterface $targetState, ?string $eventName = null, ?ConditionInterface $condition = null)
    {
        $this->targetState = $targetState;
        $this->eventName = $eventName;
        $this->condition = $condition;
    }

    public function getTargetState(): StateInterface
    {
        return $this->targetState;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function getConditionName(): ?string
    {
        if ($this->condition) {
            return $this->condition->getName();
        }
        return null;
    }

    public function isActive(object $subject, \ArrayAccess $context, ?EventInterface $event = null): bool
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

    public function getCondition(): ?ConditionInterface
    {
        return $this->condition;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
}
