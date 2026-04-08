<?php

namespace Metabor\Statemachine;

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
        $builder = $this->getMockBuilder('Metabor\Statemachine\NamedCommand')
            ->setConstructorArgs(array($name));
        $builder->onlyMethods($methods);

        return $builder->getMock();
    }

    public function testWillReturnNameIfCastedToAString(): void
    {
        $name = 'foo';
        $command = $this->createTestInstance(array(), $name);
        $string = (string) $command;
        $this->assertEquals($name, $string);
    }
}
