<?php

class WrongEventForStateException extends \RuntimeException
{
    /**
     * @param string $stateName
     * @param string $eventName
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($stateName, $eventName, $code = 0, \Exception $previous = null)
    {
        $message = 'Current state "' . $stateName . '" doesn\'t have event "' . $eventName . '"';
        parent::__construct($message, $code, $previous);
    }
}
