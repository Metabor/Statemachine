<?php
namespace MetaborStd\Statemachine;
use MetaborStd\NamedInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface ProcessInterface extends NamedInterface, StateCollectionInterface
{

    /**
     *
     * @return \MetaborStd\Statemachine\StateInterface
     */
    public function getInitialState();

}
