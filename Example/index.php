<pre>
<?php
require_once '../autoloader.php';

use Example\Order\Process\Postpayment;
use Example\Order\ProcessConstants;
use Example\Order\EventConstants;
use Example\Order\Order;
use Example\Order\Process\Prepayment;
use Metabor\NamedCollection;

$processes = new NamedCollection ();
$processes->add ( new Prepayment () );
$processes->add ( new Postpayment () );

$orders = array ();
$orders ['PREPAYMENT 1'] = new Order ( 'PREPAYMENT 1', $processes->get ( ProcessConstants::PROCESS_PREPAYMENT ) );
$orders ['PREPAYMENT 2'] = new Order ( 'PREPAYMENT 2', $processes->get ( ProcessConstants::PROCESS_PREPAYMENT ) );
$orders ['POSTPAYMENT 1'] = new Order ( 'POSTPAYMENT 1', $processes->get ( ProcessConstants::PROCESS_POSTPAYMENT ) );
$orders ['POSTPAYMENT 2'] = new Order ( 'POSTPAYMENT 2', $processes->get ( ProcessConstants::PROCESS_POSTPAYMENT ) );

echo '=============================================================' . PHP_EOL;
echo 'all created orders have the status "new"' . PHP_EOL;
echo '=============================================================' . PHP_EOL;
foreach ( $orders as $order ) {
	echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	echo 'possible events: ' . implode ( ', ', $order->getEventNames () ) . PHP_EOL;
	echo '-------------------------------------------------------------' . PHP_EOL;
}

echo '=============================================================' . PHP_EOL;
echo 'now we are authorizing all orders if possible' . PHP_EOL;
echo '=============================================================' . PHP_EOL;
foreach ( $orders as $order ) {
	echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	if ($order->hasEvent ( EventConstants::EVENT_AUTHORIZE )) {
		$order->triggerEvent ( EventConstants::EVENT_AUTHORIZE );
		echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	}
	
	if ($order->hasEvent ( EventConstants::EVENT_PAID )) {
		$order->triggerEvent ( EventConstants::EVENT_PAID );
		echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	}
	
	echo '-------------------------------------------------------------' . PHP_EOL;
}

echo '=============================================================' . PHP_EOL;
echo 'now we are shipping all orders if possible' . PHP_EOL;
echo '=============================================================' . PHP_EOL;
foreach ( $orders as $order ) {
	echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	if ($order->hasEvent ( EventConstants::EVENT_SHIPPING )) {
		$order->triggerEvent ( EventConstants::EVENT_SHIPPING );
		echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	}
	
	echo '-------------------------------------------------------------' . PHP_EOL;
}

echo '=============================================================' . PHP_EOL;
echo 'now all orders will be returned' . PHP_EOL;
echo '=============================================================' . PHP_EOL;

foreach ( $orders as $order ) {
	$eventName = EventConstants::EVENT_RETURNED;
	echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	try {
		$order->triggerEvent ( $eventName );
	} catch ( Exception $e ) {
		echo 'Triggering the event "' . EventConstants::EVENT_RETURNED . '" on order "' . $order . '" throws an error: ' . $e->getMessage () . PHP_EOL;
	}
	echo $order . ' has status ' . $order->getCurrentStateName () . PHP_EOL;
	echo '-------------------------------------------------------------' . PHP_EOL;
}

?>
</pre>