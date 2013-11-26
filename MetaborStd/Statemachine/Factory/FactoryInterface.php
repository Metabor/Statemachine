<?php
namespace MetaborStd\Statemachine\Factory;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface FactoryInterface
{
    /**
     * 
     * @param object $subject
     * @return \MetaborStd\Statemachine\StatemachineInterface
     */
    public function createStatemachine($subject);
}
