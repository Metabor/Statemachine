<?php

namespace Metabor\Statemachine\Factory;

/**
 * @author Oliver Tischlinger
 */
class StatefulStateNameDetectorTest extends \PHPUnit\Framework\TestCase
{
    public function testReturnsTheCurrentStateFromAStatefulObject(): void
    {
        $name = 'TestStatus';
        $subject = $this->createMock('\MetaborStd\Statemachine\StatefulInterface');
        $subject->expects($this->once())->method('getCurrentStateName')->willReturn($name);

        $detector = new StatefulStateNameDetector();
        $result = $detector->detectCurrentStateName($subject);

        $this->assertEquals($name, $result);
    }

    public function testThrowsAnExscptionIfObjectIsNotStateful(): void
    {
        $subject = new \stdClass();
        $detector = new StatefulStateNameDetector();
        $this->expectException('\InvalidArgumentException');
        $detector->detectCurrentStateName($subject);
    }
}
