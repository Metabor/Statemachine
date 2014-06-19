<?php
namespace Metabor\Statemachine\Factory\TransitionSelector;
use MetaborStd\Statemachine\TransitionInterface;
use Traversable;
use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;

/**
 * @author otischlinger
 *
 */
class ScoreTransition implements TransitionSelectorInterface
{

    /**
     * @param TransitionInterface $transition
     * @return integer
     */
    protected function calculcateScore(TransitionInterface $transition)
    {
        $score = 0;
        if ($transition->getEventName()) {
            $score += 2;
        }
        if ($transition->getConditionName()) {
            $score++;
        }
        return $score;
    }

    /**
     * @see \MetaborStd\Statemachine\Factory\TransitionSelectorInterface::selectTransition()
     */
    public function selectTransition(Traversable $transitions)
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
        $disctinctSelector = new OneOrNoneActiveTransition();
        return $disctinctSelector->selectTransition(new \ArrayIterator($bestTransitions));
    }
}
