<?php

namespace Metabor\Statemachine\Condition;

use Metabor\Named;
use MetaborStd\Statemachine\ConditionInterface;

/**
 * @author Oliver Tischlinger
 */
class Callback extends Named implements ConditionInterface
{
    /** @var callable */
    private $callable;

    public function __construct(string $name, callable $callable)
    {
        parent::__construct($name);
        $this->callable = $callable;
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        return (bool) ($this->callable)($subject, $context);
    }
}
