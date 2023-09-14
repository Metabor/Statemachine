<?php

namespace Metabor\Statemachine;

use Metabor\Event\Event;
use Metabor\Observer\Subject;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Oliver Tischlinger
 */
class CommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return Command|MockObject
     */
    protected function createTestInstance(array $methods = array())
    {
        $command = $this->getMockForAbstractClass(
            'Metabor\Statemachine\Command',
            array(),
            '',
            true,
            true,
            true,
            $methods
        );

        return $command;
    }

    public function testWillThrowAnExceptionIfCommandIsNotInvokable()
    {
        $command = $this->createTestInstance();
        $subject = new Event('test');
        $subject->attach($command);
        $this->expectException('\Exception');
        $subject('foo', 'bar', 'baz');
    }

    public function testDelegatesUpdateFromEventToInvoke()
    {
        $command = $this->createTestInstance(array('__invoke'));
        $subject = new Event('test');
        $subject->attach($command);
        $command->expects($this->once())->method('__invoke')->with('foo', 'bar', 'baz');
        $subject('foo', 'bar', 'baz');
    }

    public function testWillThrowAnExcpetionIfObserverIsNotAnEvent()
    {
        $command = $this->createTestInstance(array('__invoke'));
        $subject = new Subject();
        $subject->attach($command);
        $this->expectException('\Exception');
        $subject->notify();
    }

    public function testIsCastableToAString()
    {
        $command = $this->createTestInstance();
        $string = (string) $command;
        $this->assertNotEmpty($string);
    }
}
