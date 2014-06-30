<?php
namespace Metabor\KeyValue;

/**
 * 
 * @author Oliver Tischlinger
 *
 */
class CompositeTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testDistributesValuesToContainedArrayAccessObjects()
    {
        $composite = new Composite();
        $keyValue1 = new \ArrayIterator();
        $composite->attach($keyValue1);
        $keyValue2 = new \ArrayIterator();
        $composite->attach($keyValue2);

        $key = 'Key';
        $value = 'Value';
        $composite[$key] = $value;

        $this->assertArrayHasKey($key, $keyValue1);
        $this->assertArrayHasKey($key, $keyValue2);

        $this->assertEquals($value, $keyValue1[$key]);
        $this->assertEquals($value, $keyValue2[$key]);
    }

    /**
     *
     */
    public function testHandleKeyAsExistingWhenAllContainedArrayAccessObjectsHaveThisKey()
    {
        $key = 'Key';
        $value = 'Value';

        $array = array($key => $value);
        $composite = new Composite();
        $keyValue1 = new \ArrayIterator($array);
        $composite->attach($keyValue1);
        $keyValue2 = new \ArrayIterator($array);
        $composite->attach($keyValue2);

        $this->assertArrayHasKey($key, $composite);
    }

    /**
     *
     */
    public function testReturnsKeyValueWhenAllContainedArrayAccessObjectsHaveThisKeyValuePair1()
    {
        $key = 'Key';
        $value = 'Value';

        $array = array($key => $value);
        $composite = new Composite();
        $keyValue1 = new \ArrayIterator($array);
        $composite->attach($keyValue1);
        $keyValue2 = new \ArrayIterator($array);
        $composite->attach($keyValue2);

        $this->assertEquals($value, $composite[$key]);
    }

    /**
     *
     */
    public function testReturnsKeyValueWhenAllContainedArrayAccessObjectsHaveThisKeyValuePair2()
    {
        $composite = new Composite();
        $keyValue1 = new \ArrayIterator();
        $composite->attach($keyValue1);
        $keyValue2 = new \ArrayIterator();
        $composite->attach($keyValue2);

        $key = 'Key';
        $value = 'Value';
        $composite[$key] = $value;

        $this->assertEquals($value, $composite[$key]);
    }

}
