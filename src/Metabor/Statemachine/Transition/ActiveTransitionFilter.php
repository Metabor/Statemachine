<?php
namespace Metabor\Statemachine\Transition;
use MetaborStd\Event\EventInterface;

/**
 * @author otischlinger
 *
 */
class ActiveTransitionFilter extends \FilterIterator
{
    /**
     * @var object
     */
    protected $subject;

    /**
     * @var ArrayAccess
     */
    protected $context;

    /**
     * @var EventInterface
     */
    protected $event;

    /**
     * @param \Traversable $transitions
     * @param object $subject        	
     * @param \ArrayAccess $context        	
     * @param EventInterface $event
     */
    public function __construct(\Traversable $transitions, $subject, \ArrayAccess $context, EventInterface $event = null)
    {
        parent::__construct(new \IteratorIterator($transitions));
        $this->subject = $subject;
        $this->context = $context;
        $this->event = $event;
    }

    /**
     * @see FilterIterator::accept()
     *
     */
    public function accept()
    {
        $transition = $this->current();
        return $transition->isActive($this->subject, $this->context, $this->event);
    }

}
