<?php
namespace Example\Order\Process;

use MetaborStd\Event\EventInterface;
use Example\Order\Command\Authorize;
use Example\Order\Condition\ShippingDateGreater14Days;
use Example\Order\Condition\AuthorizedSuccessful;
use Metabor\Callback\Callback;
use Metabor\Observer\Callback as ObserverCallback;
use Metabor\Statemachine\Condition\Not;
use Example\Order\StateConstants;
use Example\Order\EventConstants;
use Example\Order\ProcessConstants;
use Metabor\Statemachine\Process;
use Metabor\Statemachine\Transition;
use Metabor\Statemachine\State;

class Prepayment extends Process implements ProcessConstants, StateConstants, EventConstants
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
        $paymentFailed = new State(self::STATE_PAYMENT_FAILED);
        $paymentPending = new State(self::STATE_PAYMENT_PENDING);
        $shippable = new State(self::STATE_SHIPPABLE);
        $shipped = new State(self::STATE_SHIPPED);
        $closed = new State(self::STATE_CLOSED);
        $returned = new State(self::STATE_RETURNED);
        $returnedAndClosed = new State(self::STATE_RETURNED_AND_CLOSED);

        $event = $new->getEvent(self::EVENT_AUTHORIZE);
        $command = new Authorize();
        $event->attach($command);

        $event = $shippable->getEvent(self::EVENT_SHIPPING);
        $command = function (EventInterface $event)
        {
            list($order, $context) = $event->getInvokeArgs();
            echo 'Command: ' . $order . ' is shipped!' . PHP_EOL;
        };
        $this->addCommand($event, $command);

        $shippingDateGreater14Days = new ShippingDateGreater14Days();
        $authorizeSuccessful = new AuthorizedSuccessful();
        $authorizeFailed = new Not($authorizeSuccessful);

        $new->addTransition(new Transition($paymentFailed, self::EVENT_AUTHORIZE, $authorizeFailed));
        $new->addTransition(new Transition($paymentPending, self::EVENT_AUTHORIZE, $authorizeSuccessful));
        $paymentPending->addTransition(new Transition($shippable, self::EVENT_PAID));
        $shippable->addTransition(new Transition($shipped, self::EVENT_SHIPPING));
        $shipped->addTransition(new Transition($closed, null, $shippingDateGreater14Days));
        $shipped->addTransition(new Transition($returned, self::EVENT_RETURNED));
        $closed->addTransition(new Transition($returned, self::EVENT_RETURNED));
        $returned->addTransition(new Transition($returnedAndClosed, self::EVENT_REFUND));

        parent::__construct(self::PROCESS_PREPAYMENT, $new);
    }
}
