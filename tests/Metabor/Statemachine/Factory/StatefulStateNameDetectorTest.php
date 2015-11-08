<?php

namespace Metabor\Statemachine\Factory;

/**
 * @author Oliver Tischlinger
 */
class StatefulStateNameDetectorTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsTheCurrentStateFromAStatefulObject()
    {
        $name = 'TestStatus';
        $subject = $this->getMockForAbstractClass('\MetaborStd\Statemachine\StatefulInterface');
        $subject->expects($this->once())->method('getCurrentStateName')->willReturn($name);

        $detector = new StatefulStateNameDetector();
        $result = $detector->detectCurrentStateName($subject);

        $this->assertEquals($name, $result);
    }

    public function testThrowsAnExscptionIfObjectIsNotStateful()
    {
        $subject = new \stdClass();
        $detector = new StatefulStateNameDetector();
        $this->setExpectedException('\InvalidArgumentException');
        $detector->detectCurrentStateName($subject);
    }
}
