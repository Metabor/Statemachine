<?php

namespace Metabor\Callback;

use MetaborStd\CallbackInterface;

/**
 * @author Oliver Tischlinger
 */
class Callback implements CallbackInterface
{
    /** @var callable */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function __invoke(mixed ...$args): mixed
    {
        return ($this->callable)(...$args);
    }
}
