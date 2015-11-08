<?php

namespace Metabor\Callback;

use InvalidArgumentException;
use MetaborStd\CallbackInterface;

/**
 * @author Oliver Tischlinger
 */
class Callback implements CallbackInterface
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException('Argument is not callable!');
        }
        $this->callable = $callable;
    }

    /**
     * @see MetaborStd.CallbackInterface::__invoke()
     */
    public function __invoke()
    {
        $args = func_get_args();

        return call_user_func_array($this->callable, $args);
    }
}
