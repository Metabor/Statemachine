<?php
namespace MetaborStd\Statemachine;
use Traversable;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface StateCollectionInterface
{

    /**
     *
     * @return Traversable
     */
    public function getStates();

    /**
     *
     * @param string $name            
     * @return StateInterface
     */
    public function getState($name);

    /**
     *
     * @param string $name            
     * @return boolean
     */
    public function hasState($name);

}
