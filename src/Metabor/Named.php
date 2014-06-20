<?php
namespace Metabor;
use MetaborStd\NamedInterface;

/**
 *
 * @author Oliver Tischlinger
 *
 */
class Named implements NamedInterface
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }
}
