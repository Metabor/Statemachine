<?php
// Deactivate Strict mode, because Image_GraphViz class didn't run
ini_set ( 'display_errors', '0' );
require_once 'Image/GraphViz.php';
require_once '../autoloader.php';

use Example\Order\Process\Postpayment;
use Example\Order\Process\Prepayment;
use Metabor\NamedCollection;
use MetaborInterface\Statemachine\StateInterface;
use MetaborInterface\Statemachine\TransitionInterface;

$processes = new NamedCollection ();
$processes->add ( new Prepayment () );
$processes->add ( new Postpayment () );

if (isset ( $_GET ['process'] )) {
	$processName = strtolower ( $_GET ['process'] );
	if ($processes->has ( $processName )) {
		$process = $processes->get ( $processName );
	}
}
if (! $process) {
	$processName = reset ( $processes->getNames () );
	$process = $processes->get ( $processName );
}

$graphViz = new Image_GraphViz ();

/* @var $state StateInterface */
foreach ( $process->getStates () as $state ) {
	$sourceStateName = $state->getName ();
	/* @var $transition TransitionInterface */
	foreach ( $state->getTransitions () as $transition ) {
		$targetStateName = $transition->getTargetState ()->getName ();
		$labelParts = array ();
		$eventName = $transition->getEventName ();
		if ($eventName) {
			$labelParts [] = 'E: ' . $eventName;
		}
		$conditionName = $transition->getConditionName ();
		if ($conditionName) {
			$labelParts [] = 'C: ' . $conditionName;
		}
		$label = implode ( PHP_EOL, $labelParts );
		$graphViz->addEdge ( array (
				$sourceStateName => $targetStateName 
		), array (
				'label' => $label 
		) );
	}
}

$graphViz->image ();