<?php
namespace Metabor\Observer;

/**
 * 
 * @author Oliver Tischlinger
 *
 */
class SubjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testImplementsSubjectPartOfObserverPattern()
    {
        $subject = new Subject();

        $observerA = $this->getMockForAbstractClass('\SplObserver');
        $observerA->expects($this->once())->method('update')->with($subject);
        $subject->attach($observerA);

        $observerB = $this->getMockForAbstractClass('\SplObserver');
        $observerB->expects($this->never())->method('update')->with($subject);
        $subject->attach($observerB);
        $subject->detach($observerB);

        $subject->notify();
    }

    /**
     *
     */
    public function testObserverCanBeAttachedAndDetach()
    {
        $subject = new Subject();

        $observerA = $this->getMockForAbstractClass('\SplObserver');
        $subject->attach($observerA);

        $observerB = $this->getMockForAbstractClass('\SplObserver');
        $subject->attach($observerB);
        $subject->detach($observerB);

        $this->assertContains($observerA, $subject->getObservers());
        $this->assertNotContains($observerB, $subject->getObservers());
    }
}
