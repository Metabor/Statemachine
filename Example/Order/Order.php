<?php
namespace Example\Order;

use Metabor\Statemachine\Statemachine;
use MetaborStd\Statemachine\ProcessInterface;
use MetaborStd\Statemachine\StatemachineInterface;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Order
{

    /**
     *
     * @var string
     */
    private $number;

    /**
     *
     * @var StatemachineInterface
     */
    private $statemachine;

    /**
     *
     * @param string $number        	
     * @param ProcessInterface $process        	
     */
    public function __construct($number, ProcessInterface $process)
    {
        $this->number = $number;
        $this->statemachine = new Statemachine($this, $process);
    }

    /**
     *
     * @param string $name        	
     * @param ArrayAccess $context        	
     */
    public function triggerEvent($name, ArrayAccess $context = null)
    {
        echo 'trigger event "' . $name . '" on ' . $this . PHP_EOL;
        $this->statemachine->triggerEvent($name, $context);
    }

    /**
     *
     * @param string $name        	
     * @return boolean
     */
    public function hasEvent($name)
    {
        return $this->statemachine->getCurrentState()->hasEvent($name);
    }

    /**
     *
     * @return \Traversable
     */
    public function getEventNames()
    {
        return $this->statemachine->getCurrentState()->getEventNames();
    }

    /**
     *
     * @return string
     */
    public function getCurrentStateName()
    {
        return $this->statemachine->getCurrentState()->getName();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return 'Order ' . $this->number;
    }

    /**
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

}
