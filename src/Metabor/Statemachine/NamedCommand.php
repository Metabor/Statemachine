<?php

namespace Metabor\Statemachine;

use MetaborStd\NamedInterface;

/**
 * @author Oliver Tischlinger
 */
abstract class NamedCommand extends Command implements NamedInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @see \MetaborStd\NamedInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * I would require to provide a name because its really needed for visualisation
     * and the class name might be very long and not very descriptive.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
