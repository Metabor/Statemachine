<?php
namespace Metabor\Statemachine\Factory\TransitionSelector;

use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;

/**
 * @author otischlinger
 */
class OneOrNoneActiveTransition implements TransitionSelectorInterface
{
    /**
     * @see \MetaborStd\Statemachine\Factory\TransitionSelectorInterface::selectTransition()
     */
    public function selectTransition(\Traversable $transitions)
    {
        $transitions = iterator_to_array($transitions);

        switch (count($transitions)) {
            case 0:
                return;
            case 1:
                return reset($transitions);
            default:
                throw new \RuntimeException('More than one transition is active!');
        }
    }
}
