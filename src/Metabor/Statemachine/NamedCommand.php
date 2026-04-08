<?php

namespace Metabor\Statemachine;

use Metabor\Named;

/**
 * @author Oliver Tischlinger
 */
abstract class NamedCommand extends Command
{
    private string $commandName;

    public function __construct(string $commandName)
    {
        $this->commandName = $commandName;
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function getName(): string
    {
        return $this->commandName;
    }

    public function __toString(): string
    {
        return $this->commandName;
    }
}
