<?php

namespace Metabor\Statemachine\Factory\TransitionSelector;

use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use MetaborStd\Statemachine\TransitionInterface;
use MetaborStd\WeightedInterface;

/**
 * @author Oliver Tischlinger
 */
class WeightTransition extends ScoreTransition
{
    private float $epsilon;

    public function __construct(?TransitionSelectorInterface $innerSelector = null, float $epsilon = 0.0001)
    {
        parent::__construct($innerSelector);
        $this->epsilon = $epsilon;
    }

    protected function getScore(TransitionInterface $transition): float
    {
        if ($transition instanceof WeightedInterface) {
            return $transition->getWeight();
        }
        return parent::getScore($transition);
    }
}
