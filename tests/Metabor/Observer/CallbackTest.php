<?php

namespace Metabor\Observer;

/**
 * @author Oliver Tischlinger
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testConvertsACallbackIntoAnObserver()
    {
        $subject = $this->getMockForAbstractClass('\SplSubject');

        $callback = $this->getMockForAbstractClass('\MetaborStd\CallbackInterface');
        $callback->expects($this->once())->method('__invoke')->with($subject);

        $observer = new Callback($callback);
        $observer->update($subject);
    }
}
