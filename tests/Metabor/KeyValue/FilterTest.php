<?php

namespace Metabor\KeyValue;

/**
 * @author Oliver Tischlinger
 */
class FilterTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testFiltersArrayAccessObjectsByCriteria(): void
    {
        $key = 'test';
        $value1 = 'v1';
        $value2 = 'v2';

        $iterator = new \SplObjectStorage();
        $keyValue1 = new \ArrayIterator();
        $keyValue1[$key] = $value1;
        $iterator->offsetSet($keyValue1, null);
        $keyValue2 = new \ArrayIterator();
        $keyValue2[$key] = $value2;
        $iterator->offsetSet($keyValue2, null);

        $criteria = new Criteria(array($key => $value1));
        $filtered = new Filter($iterator, $criteria);

        $this->assertContains($keyValue1, $filtered);
        $this->assertNotContains($keyValue2, $filtered);
    }
}
