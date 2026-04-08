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
    public function testWillStayFalseUntilLastStateHasChangedDateReachedTimeoutIntervall(): void
    {
        $instance = $this->createTestInstance();
        $subject = $this->createMock('MetaborStd\Statemachine\LastStateHasChangedDateInterface');
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
    public function testWillBecomeTrueAfterLastStateHasChangedDateReachedTimeoutIntervall(): void
    {
        $instance = $this->createTestInstance();
        $subject = $this->createMock('MetaborStd\Statemachine\LastStateHasChangedDateInterface');
        $subject->expects($this->atLeastOnce())
            ->method('getLastStateHasChangedDate')
            ->willReturn(new \DateTime('1 week ago'));
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertTrue($result);
    }
}
