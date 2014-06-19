<?php
namespace Example\Order\Process;

use Example\Order\Condition\ShippingDateGreater14Days;
use MetaborStd\Event\EventInterface;
use Metabor\Callback\Callback;
use Metabor\Observer\Callback as ObserverCallback;
use Example\Order\StateConstants;
use Example\Order\EventConstants;
use Example\Order\ProcessConstants;
use Metabor\Statemachine\Process;
use Metabor\Statemachine\Transition;
use Metabor\Statemachine\State;

class Postpayment extends Process implements ProcessConstants, StateConstants, EventConstants
{

    /**
     *
     * @param EventInterface $event
     * @param callable $command
     */
    protected function addCommand(EventInterface $event, $command)
    {
        $callback = new Callback($command);
        $observer = new ObserverCallback($callback);
        $event->attach($observer);
    }

    public function __construct()
    {
        $new = new State(self::STATE_NEW);
        $shipped = new State(self::STATE_SHIPPED);
        $dunning = new State(self::STATE_DUNNING);
        $paid = new State(self::STATE_PAID);
        $closed = new State(self::STATE_CLOSED);
        $returned = new State(self::STATE_RETURNED);
        $returnedAndClosed = new State(self::STATE_RETURNED_AND_CLOSED);

        $event = $new->getEvent(self::EVENT_SHIPPING);
        $command = function (EventInterface $event)
        {
            list($order, $context) = $event->getInvokeArgs();
            echo 'Command: ' . $order . ' is shipped!' . PHP_EOL;
        };
        $this->addCommand($event, $command);

        $shippingDateGreater14Days = new ShippingDateGreater14Days();

        $new->addTransition(new Transition($shipped, self::EVENT_SHIPPING));
        $shipped->addTransition(new Transition($dunning, null, $shippingDateGreater14Days));
        $shipped->addTransition(new Transition($paid, self::EVENT_PAID));
        $shipped->addTransition(new Transition($returnedAndClosed, self::EVENT_RETURNED));
        $dunning->addTransition(new Transition($returnedAndClosed, self::EVENT_RETURNED));
        $dunning->addTransition(new Transition($closed, self::EVENT_PAID));
        $paid->addTransition(new Transition($closed, null, $shippingDateGreater14Days));
        $paid->addTransition(new Transition($returned, self::EVENT_RETURNED));
        $closed->addTransition(new Transition($returned, self::EVENT_RETURNED));
        $returned->addTransition(new Transition($returnedAndClosed, self::EVENT_REFUND));

        parent::__construct(self::PROCESS_POSTPAYMENT, $new);
    }

}
