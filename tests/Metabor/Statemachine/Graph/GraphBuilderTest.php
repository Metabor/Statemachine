<?php

namespace Metabor\Statemachine\Graph;

use Fhaculty\Graph\Graph;
use Metabor\Statemachine\State;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Transition;

/**
 * @author Oliver Tischlinger
 */
class GraphBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testAddsStatesToGraph()
    {
        $state = new State('first');
        $stateCollection = new StateCollection();
        $stateCollection->addState($state);
        $secondState = new State('second');
        $state->addTransition(new Transition($secondState));

        $graph = new Graph();
        $builder = new GraphBuilder($graph);
        $builder->addStateCollection($stateCollection);
        $this->assertTrue($graph->hasVertex('first'));
        $this->assertTrue($graph->hasVertex('second'));
    }
}
