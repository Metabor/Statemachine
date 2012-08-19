<?php
namespace Metabor54;
use MetaborInterface\NamedInterface;
use MetaborTrait\NamedTrait;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Named implements NamedInterface
{
    use NamedTrait;

    /**
     *
     * @param string $name            
     */
    public function __construct ($name)
    {
        $this->setName($name);
    }
}