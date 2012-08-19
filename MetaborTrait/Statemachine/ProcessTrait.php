<?php
namespace MetaborTrait\Statemachine;
use MetaborTrait\NamedTrait;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
trait ProcessTrait
{
    use NamedTrait;

    /**
     *
     * @return \Traversable
     */
    public function getStates ()
    {

    }

    /**
     *
     * @return \MetaborInterface\Statemachine\StateInterface
     */
    public function getInitialState ()
    {

    }

}