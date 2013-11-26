<?php
namespace MetaborStd\Statemachine;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface StatefulInterface
{

    /**
     *
     * @return string
     */
    public function getCurrentStateName();

    /**
     * @param string $stateName
     */
    public function setCurrentStateName($stateName);

}
