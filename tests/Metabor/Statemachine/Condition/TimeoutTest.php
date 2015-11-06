<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class TimeoutTest extends NamedInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new Timeout('1 week');
    }

    /**
     *
     */
    public function testWillStayFalseUntilLastStateHasChangedDateReachedTimeoutIntervall()
    {
        $instance = $this->createTestInstance();
        $subject = $this->getMockForAbstractClass('MetaborStd\Statemachine\LastStateHasChangedDateInterface');
        $subject->expects($this->atLeastOnce())
        ->method('getLastStateHasChangedDate')
        ->willReturn(new \DateTime());
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testWillBecomeTrueAfterLastStateHasChangedDateReachedTimeoutIntervall()
    {
        $instance = $this->createTestInstance();
        $subject = $this->getMockForAbstractClass('MetaborStd\Statemachine\LastStateHasChangedDateInterface');
        $subject->expects($this->atLeastOnce())
            ->method('getLastStateHasChangedDate')
            ->willReturn(new \DateTime('1 week ago'));
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertTrue($result);
    }
}
