<?php

namespace Metabor\Callback;

use PHPUnit\Framework\TestCase;

/**
 * @author Oliver Tischlinger
 */
class CallbackTest extends TestCase
{
    private bool $wasCalled;

    private mixed $parameter;

    public function callableMethod(): void
    {
        $this->wasCalled = true;
    }

    public function __invoke($parameter): void
    {
        $this->wasCalled = true;
        $this->assertSame($this->parameter, $parameter);
    }

    /**
     *
     */
    public function testConvertsCallableToCallback(): void
    {
        $callable = array($this, 'callableMethod');
        $callback = new Callback($callable);
        $this->wasCalled = false;
        $callback();
        $this->assertTrue($this->wasCalled);
    }

    /**
     *
     */
    public function testDeligateAllParamterToTheContainedCallableWhenInvoked(): void
    {
        $callback = new Callback($this);
        $this->parameter = new \stdClass();
        $this->wasCalled = false;
        $callback($this->parameter);
        $this->assertTrue($this->wasCalled);
    }
}
