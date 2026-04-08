<?php

namespace Metabor\Statemachine\Factory\TransitionSelector;

use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author otischlinger
 */
class OneOrNoneActiveTransition implements TransitionSelectorInterface
{
    public function selectTransition(\Traversable $transitions): ?TransitionInterface
    {
        $transitions = iterator_to_array($transitions);

        return match (count($transitions)) {
            0 => null,
            1 => reset($transitions),
            default => throw new \RuntimeException('More than one transition is active!'),
        };
    }
}
