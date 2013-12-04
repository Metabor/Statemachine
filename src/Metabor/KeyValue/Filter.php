<?php
namespace Metabor\KeyValue;
use FilterIterator;
use ArrayAccess;
use Iterator;
use ArrayAccess;

/**
 * @author otischlinger
 *
 */
class Filter extends FilterIterator
{
    /**
     * @var Criteria
     */
    private $criteria;

    /**
     * @param Iterator $iterator
     */
    public function __construct(Iterator $iterator, Criteria $criteria)
    {
        parent::__construct($iterator);
        $this->criteria = $criteria;
    }

    /**
     * @see FilterIterator::accept()
     */
    public function accept()
    {
        $current = $this->current();
        return (($current instanceof ArrayAccess) && $this->criteria->check($current));
    }

}
