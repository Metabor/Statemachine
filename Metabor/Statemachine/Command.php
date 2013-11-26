<?php
namespace Metabor\Statemachine;
use MetaborStd\Event\EventInterface;

use SplObserver;
use SplSubject;
use InvalidArgumentException;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
abstract class Command implements SplObserver
{
    /**
     *
     * @param SplSubject $subject        	
     * @throws InvalidArgumentException
     */
    public function update(SplSubject $subject)
    {
        if (!$subject instanceof EventInterface) {
            throw new InvalidArgumentException('Command can only be attached to an event!');
        }
        call_user_func_array($this, $subject->getInvokeArgs());
    }

}
