<?php

namespace Metabor\Observer;

/**
 * @author Oliver Tischlinger
 */
class SubjectTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testImplementsSubjectPartOfObserverPattern(): void
    {
        $subject = new Subject();

        $observerA = $this->createMock('\SplObserver');
        $observerA->expects($this->once())->method('update')->with($subject);
        $subject->attach($observerA);

        $observerB = $this->createMock('\SplObserver');
        $observerB->expects($this->never())->method('update')->with($subject);
        $subject->attach($observerB);
        $subject->detach($observerB);

        $subject->notify();
    }

    /**
     *
     */
    public function testObserverCanBeAttachedAndDetach(): void
    {
        $subject = new Subject();

        $observerA = $this->createMock('\SplObserver');
        $subject->attach($observerA);

        $observerB = $this->createMock('\SplObserver');
        $subject->attach($observerB);
        $subject->detach($observerB);

        $this->assertContains($observerA, $subject->getObservers());
        $this->assertNotContains($observerB, $subject->getObservers());
    }
}
