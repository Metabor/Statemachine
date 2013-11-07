<?php
require_once '../autoloader.php';

use Example\Order\Process\Postpayment;
use Example\Order\Process\Prepayment;
use Metabor\NamedCollection;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\GraphViz;
try {
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
	
	$graph = new Graph ();
	
	/* @var $state StateInterface */
	foreach ( $process->getStates () as $state ) {
		$sourceStateName = $state->getName ();
		$sourceStateVertex = $graph->createVertex ( $sourceStateName, true );
		
		/* @var $transition TransitionInterface */
		foreach ( $state->getTransitions () as $transition ) {
			$targetStateName = $transition->getTargetState ()->getName ();
			$targetStateVertex = $graph->createVertex ( $targetStateName, true );
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
			$edge = $sourceStateVertex->createEdgeTo ( $targetStateVertex );
			$edge->setLayoutAttribute('label', $label);
		}
	}
	
	$viz = new GraphViz ( $graph );
	$viz->setExecutable('"C:\\Program Files (x86)\\Graphviz2.34\\bin\\dot.exe"');
	$viz->setFormat ( 'svg' );
	echo file_get_contents($viz->createImageFile());
} catch ( Exception $e ) {
	echo $e->getMessage ();
}