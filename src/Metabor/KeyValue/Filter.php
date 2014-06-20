<?php
namespace Metabor\KeyValue;
/**
 * @author otischlinger
 *
 */
class Filter extends \FilterIterator
{
    /**
     * @var Criteria
     */
    private $criteria;

    /**
     * @param \Traversable $iterator
     */
    public function __construct(\Traversable $iterator, Criteria $criteria)
    {
        parent::__construct(new \IteratorIterator($iterator));
        $this->criteria = $criteria;
    }

    /**
     * @see FilterIterator::accept()
     */
    public function accept()
    {
        $current = $this->current();

        return (($current instanceof \ArrayAccess) && $this->criteria->check($current));
    }
}
