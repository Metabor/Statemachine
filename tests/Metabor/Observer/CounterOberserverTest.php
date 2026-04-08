<?php

namespace Metabor\Observer;

/**
 * @author Oliver Tischlinger
 */
class CounterOberserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testCountsHowOftenTheObserverWasCalled(): void
    {
        $subject = $this->createMock('\SplSubject');

        $observer = new CounterObserver();
        $this->assertEquals(0, $observer->getCount());
        $observer->update($subject);
        $observer->update($subject);
        $this->assertEquals(2, $observer->getCount());
    }

    /**
     *
     */
    public function testCanBeReset(): void
    {
        $subject = $this->createMock('\SplSubject');

        $observer = new CounterObserver();
        $this->assertEquals(0, $observer->getCount());
        $observer->update($subject);
        $this->assertEquals(1, $observer->getCount());
        $observer->resetCount();
        $this->assertEquals(0, $observer->getCount());
    }
}
