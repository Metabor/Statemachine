<?php

namespace Metabor;

use MetaborStd\NamedInterface;

/**
 * @author Oliver Tischlinger
 */
class Named implements NamedInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function setName(string $name): void
    {
        $this->name = $name;
    }
}
