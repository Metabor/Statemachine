<?php
namespace MetaborInterface\Statemachine;
use MetaborInterface\NamedInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface ProcessInterface extends NamedInterface, StateCollectionInterface
{

    /**
     *
     * @return \MetaborInterface\Statemachine\StateInterface
     */
    public function getInitialState ();

}