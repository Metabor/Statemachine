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
        $builder = $this->getMockBuilder('Metabor\Statemachine\Command');
        $builder->onlyMethods($methods);

        return $builder->getMock();
    }

    public function testWillThrowAnExceptionIfCommandIsNotInvokable(): void
    {
        $command = $this->createTestInstance();
        $subject = new Event('test');
        $subject->attach($command);
        $this->expectException('\Exception');
        $subject('foo', 'bar', 'baz');
    }

    public function testDelegatesUpdateFromEventToInvoke(): void
    {
        $command = $this->createTestInstance(array('__invoke'));
        $subject = new Event('test');
        $subject->attach($command);
        $command->expects($this->once())->method('__invoke')->with('foo', 'bar', 'baz');
        $subject('foo', 'bar', 'baz');
    }

    public function testWillThrowAnExcpetionIfObserverIsNotAnEvent(): void
    {
        $command = $this->createTestInstance(array('__invoke'));
        $subject = new Subject();
        $subject->attach($command);
        $this->expectException('\Exception');
        $subject->notify();
    }

    public function testIsCastableToAString(): void
    {
        $command = $this->createTestInstance();
        $string = (string) $command;
        $this->assertNotEmpty($string);
    }
}
