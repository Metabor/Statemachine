<?php

namespace Metabor\Statemachine\Exception;

class WrongEventForStateException extends \RuntimeException
{
    /**
     * The event that was triggered.
     *
     * @var string
     */
    protected $eventName;

    /**
     * The subject's current state.
     *
     * @var string
     */
    protected $stateName;

    /**
     * @param string     $stateName
     * @param string     $eventName
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($stateName, $eventName, $code = 0, \Exception $previous = null)
    {
        $this->stateName = $stateName;
        $this->eventName = $eventName;

        $message = 'Current state "' . $stateName . '" doesn\'t have event "' . $eventName . '"';
        parent::__construct($message, $code, $previous);
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getStateName()
    {
        return $this->stateName;
    }
}
