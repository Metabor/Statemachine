<?php

namespace Metabor\KeyValue;

/**
 * @author Oliver Tischlinger
 */
class CriteriaTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testChecksIfAllKeyValuesAreTheSameInTheArrayAccessObject()
    {
        $array = array();
        $array['Foo'] = 'bar';
        $array['bar'] = 'baz';
        $criteria = new Criteria($array);

        $array = array();
        $array['Foo'] = 'bar';
        $array['bar'] = 'baz';
        $array['additionalKey'] = 'this is ignored';
        $keyvalue = new \ArrayIterator($array);
        $result = $criteria->check($keyvalue);

        $this->assertTrue($result);
    }

    /**
     *
     */
    public function testChecksIsFalseIfAtLeastOneKeyValuesIsDifferent()
    {
        $array = array();
        $array['Foo'] = 'bar';
        $array['bar'] = 'baz';
        $array['willFail'] = 'notThere';
        $criteria = new Criteria($array);

        $array = array();
        $array['Foo'] = 'bar';
        $array['bar'] = 'baz';
        $array['additionalKey'] = 'this is ignored';
        $keyvalue = new \ArrayIterator($array);
        $result = $criteria->check($keyvalue);

        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testChecksIsFalseIfAtLeastOneKeyValuesIsDifferent2()
    {
        $array = array();
        $array['Foo'] = 'bar';
        $array['bar'] = 'baz';
        $array['differentType'] = '0';
        $criteria = new Criteria($array);

        $array = array();
        $array['Foo'] = 'bar';
        $array['bar'] = 'baz';
        $array['differentType'] = 0;
        $keyvalue = new \ArrayIterator($array);
        $result = $criteria->check($keyvalue);

        $this->assertFalse($result);
    }
}
