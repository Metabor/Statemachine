<?php
namespace MetaborTrait;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
trait NamedTrait
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @return string
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name            
     */
    protected function setName ($name)
    {
        $this->name = $name;
    }
}