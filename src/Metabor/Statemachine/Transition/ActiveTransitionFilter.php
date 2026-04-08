<?php

namespace Metabor\Statemachine\Transition;

use MetaborStd\Event\EventInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class ActiveTransitionFilter extends \FilterIterator
{
    private object $subject;
    private \ArrayAccess $context;
    private ?EventInterface $event;

    public function __construct(\Traversable $transitions, object $subject, \ArrayAccess $context, ?EventInterface $event = null)
    {
        parent::__construct(new \IteratorIterator($transitions));
        $this->subject = $subject;
        $this->context = $context;
        $this->event = $event;
    }

    public function accept(): bool
    {
        $transition = $this->current();

        return $transition instanceof TransitionInterface
            && $transition->isActive($this->subject, $this->context, $this->event);
    }
}
