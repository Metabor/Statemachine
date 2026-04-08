<?php

namespace Metabor\Statemachine\Factory\TransitionSelector;

use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class ScoreTransition implements TransitionSelectorInterface
{
    private TransitionSelectorInterface $innerSelector;

    public function __construct(?TransitionSelectorInterface $innerSelector = null)
    {
        $this->innerSelector = $innerSelector ?? new OneOrNoneActiveTransition();
    }

    protected function getScore(TransitionInterface $transition): float
    {
        $score = 0.0;
        if ($transition->getEventName() !== null) {
            $score += 1.0;
        }
        if ($transition->getConditionName() !== null) {
            $score += 1.0;
        }
        return $score;
    }

    public function selectTransition(\Traversable $transitions): ?TransitionInterface
    {
        $scored = [];
        foreach ($transitions as $transition) {
            $scored[] = [$this->getScore($transition), $transition];
        }

        if (empty($scored)) {
            return null;
        }

        usort($scored, fn($a, $b) => $b[0] <=> $a[0]);
        $maxScore = $scored[0][0];
        $topTransitions = array_filter($scored, fn($item) => $item[0] === $maxScore);
        $topTransitions = array_map(fn($item) => $item[1], $topTransitions);

        return $this->innerSelector->selectTransition(new \ArrayIterator(array_values($topTransitions)));
    }
}
