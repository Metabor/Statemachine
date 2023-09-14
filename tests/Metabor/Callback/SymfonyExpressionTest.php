<?php

namespace Metabor\Callback;

/**
 * @author Oliver Tischlinger
 */
class SymfonyExpressionTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testConvertsSymfonyExpressionToCallback()
    {
        $expression = '2 * 3';
        $callback = new SymfonyExpression($expression);
        $result = $callback();
        $this->assertEquals(6, $result);
    }

    /**
     *
     */
    public function testInvokeParametersAreConsecutivelyMappedToKeys()
    {
        $expression = 'a < b';
        $keys = array('a', 'b');
        $callback = new SymfonyExpression($expression, $keys);
        $this->assertEquals($keys, $callback->getKeys());

        $result = $callback->__invoke(2, 3);
        $this->assertTrue($result);
    }

    /**
     *
     */
    public function testExpressionIsUsedAsStringRepresentationOfTheInstance()
    {
        $expression = '2 * 3';
        $callback = new SymfonyExpression($expression);
        $this->assertEquals($expression, (string) $callback);
    }
}
