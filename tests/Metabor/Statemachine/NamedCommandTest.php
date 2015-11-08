<?php

namespace Metabor\Statemachine;

use Metabor\Event\Event;
use Metabor\Observer\Subject;

/**
 * @author Oliver Tischlinger
 */
class NamedCommandTest extends CommandTest
{
    /**
     * @return NamedCommand
     */
    protected function createTestInstance(array $methods = array(), $name = 'name')
    {
        $command = $this->getMockForAbstractClass(
            'Metabor\Statemachine\NamedCommand',
            array($name),
            '',
            true,
            true,
            true,
            $methods
        );

        return $command;
    }

    public function testWillReturnNameIfCastedToAString()
    {
        $name = 'foo';
        $command = $this->createTestInstance(array(), $name);
        $string = (string) $command;
        $this->assertEquals($name, $string);
    }
}
