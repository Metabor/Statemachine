<?php

namespace Metabor\Observer;

/**
 * @author Oliver Tischlinger
 */
class CallbackTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testConvertsACallbackIntoAnObserver(): void
    {
        $subject = $this->createMock('\SplSubject');

        $callback = $this->createMock('\MetaborStd\CallbackInterface');
        $callback->expects($this->once())->method('__invoke')->with($subject);

        $observer = new Callback($callback);
        $observer->update($subject);
    }
}
