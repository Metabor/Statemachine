<?php
namespace Metabor\KeyValue;

/**
 * @author Oliver Tischlinger
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testFiltersArrayAccessObjectsByCriteria()
    {
        $key = 'test';
        $value1 = 'v1';
        $value2 = 'v2';

        $iterator = new \SplObjectStorage();
        $keyValue1 = new \ArrayIterator();
        $keyValue1[$key] = $value1;
        $iterator->attach($keyValue1);
        $keyValue2 = new \ArrayIterator();
        $keyValue2[$key] = $value2;
        $iterator->attach($keyValue2);

        $criteria = new Criteria(array($key => $value1));
        $filtered = new Filter($iterator, $criteria);

        $this->assertContains($keyValue1, $filtered);
        $this->assertNotContains($keyValue2, $filtered);
    }
}
