<?php

namespace Metabor\Statemachine\Factory\TransitionSelector;

use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author otischlinger
 */
class ScoreTransition implements TransitionSelectorInterface
{
    /**
     * @var TransitionSelectorInterface
     */
    protected $innerSelector;

    /**
     * @param TransitionSelectorInterface|null $innerSelector
     */
    public function __construct(TransitionSelectorInterface $innerSelector = null)
    {
        if ($innerSelector) {
            $this->innerSelector = $innerSelector;
        } else {
            $this->innerSelector = new OneOrNoneActiveTransition();
        }
    }

    /**
     * @param TransitionInterface $transition
     *
     * @return int
     */
    protected function calculcateScore(TransitionInterface $transition)
    {
        $score = 0;
        if ($transition->getEventName()) {
            $score += 2;
        }
        if ($transition->getConditionName()) {
            ++$score;
        }

        return $score;
    }

    /**
     * @see \MetaborStd\Statemachine\Factory\TransitionSelectorInterface::selectTransition()
     */
    public function selectTransition(\Traversable $transitions)
    {
        $bestTransitions = array();
        $bestScore = -1;
        foreach ($transitions as $transition) {
            $score = $this->calculcateScore($transition);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestTransitions = array($transition);
            } elseif ($score == $bestScore) {
                $bestTransitions[] = $transition;
            }
        }

        return $this->innerSelector->selectTransition(new \ArrayIterator($bestTransitions));
    }
}
