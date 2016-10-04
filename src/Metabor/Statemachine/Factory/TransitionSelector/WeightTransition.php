<?php

namespace Metabor\Statemachine\Factory\TransitionSelector;

use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use MetaborStd\WeightedInterface;

/**
 * @author otischlinger
 */
class WeightTransition implements TransitionSelectorInterface
{
    /**
     * @var TransitionSelectorInterface
     */
    protected $innerSelector;

    /**
     * @var float
     */
    protected $epsilon;

    /**
     * @param TransitionSelectorInterface|null $innerSelector
     * @param float $epsilon
     */
    public function __construct(TransitionSelectorInterface $innerSelector = null, $epsilon = 0.001)
    {
        if ($innerSelector) {
            $this->innerSelector = $innerSelector;
        } else {
            $this->innerSelector = new OneOrNoneActiveTransition();
        }
        $this->epsilon = $epsilon;
    }

    /**
     * @see \MetaborStd\Statemachine\Factory\TransitionSelectorInterface::selectTransition()
     */
    public function selectTransition(\Traversable $transitions)
    {
        $bestTransitions = array();
        $bestWeight = null;
        foreach ($transitions as $transition) {
            if ($transition instanceof WeightedInterface) {
                $weight = $transition->getWeight();
                $diff = ($weight - $bestWeight);
                if (($bestWeight === null) || ($diff >= $this->epsilon)) {
                    $bestWeight = $weight;
                    $bestTransitions = array($transition);
                } elseif (abs($diff) < $this->epsilon) {
                    $bestTransitions[] = $transition;
                }
            }
        }

        return $this->innerSelector->selectTransition(new \ArrayIterator($bestTransitions));
    }
}
