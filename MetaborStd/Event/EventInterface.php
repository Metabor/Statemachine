<?php
namespace MetaborStd\Event;

use MetaborStd\CallbackInterface;
use MetaborStd\NamedInterface;
use SplSubject;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface EventInterface extends NamedInterface, SplSubject, CallbackInterface
{
    /**
     *
     * @return array
     */
    public function getInvokeArgs();
}
