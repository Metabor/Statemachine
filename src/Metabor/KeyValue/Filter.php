<?php

namespace Metabor\KeyValue;

/**
 * @author Oliver Tischlinger
 */
class Filter extends \FilterIterator
{
    private Criteria $criteria;

    public function __construct(\Traversable $iterator, Criteria $criteria)
    {
        parent::__construct(new \IteratorIterator($iterator));
        $this->criteria = $criteria;
    }

    public function accept(): bool
    {
        $current = $this->current();

        return ($current instanceof \ArrayAccess) && $this->criteria->check($current);
    }
}
