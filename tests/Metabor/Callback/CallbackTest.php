<?php
namespace Metabor\Callback;

/**
 * @author Oliver Tischlinger
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    private $wasCalled;

    private $parameter;

    public function callableMethod()
    {
        $this->wasCalled = true;
    }

    public function __invoke($parameter)
    {
        $this->wasCalled = true;
        $this->assertSame($this->parameter, $parameter);
    }

    /**
     *
     */
    public function testConvertsCallableToCallback()
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
    public function testDeligateAllParamterToTheContainedCallableWhenInvoked()
    {
        $callback = new Callback($this);
        $this->parameter = new \stdClass();
        $this->wasCalled = false;
        $callback($this->parameter);
        $this->assertTrue($this->wasCalled);
    }
}
