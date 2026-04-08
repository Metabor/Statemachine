<?php

namespace Metabor\Statemachine\Exception;

/**
 * @author Oliver Tischlinger
 */
class WrongEventForStateException extends \RuntimeException
{
    private string $stateName;
    private string $eventName;

    public function __construct(string $stateName, string $eventName, int $code = 0, ?\Throwable $previous = null)
    {
        $this->stateName = $stateName;
        $this->eventName = $eventName;

        $message = 'Current state "' . $stateName . '" doesn\'t have event "' . $eventName . '"';
        parent::__construct($message, $code, $previous);
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getStateName(): string
    {
        return $this->stateName;
    }
}
