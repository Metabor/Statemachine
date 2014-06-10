<?php
namespace Metabor\Statemachine\Util;
use Metabor\Statemachine\Condition\Tautology;

use Metabor\Statemachine\Transition;

use Metabor\Statemachine\State;

use Metabor\Statemachine\StateCollection;

/**
 * @author Oliver Tischlinger
 *
 */
class StateCollectionMergerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Metabor\Statemachine\StateCollection
     */
    protected function createSourceCollection()
    {
        $sourceCollection = new StateCollection();

        $stateNew = new State('new');
        $sourceCollection->addState($stateNew);
        $stateInProcess = new State('in progress');
        $sourceCollection->addState($stateInProcess);
        $stateDone = new State('done');
        $sourceCollection->addState($stateDone);
        
        $stateNew->addTransition(new Transition($stateInProcess, 'start'));
        $stateInProcess->addTransition(new Transition($stateDone, null, new Tautology('is finished')));

        return $sourceCollection;
    }

    public function testCreatesAllStatesFromSourceAtTargetColletions()
    {
        $targetCollection = new StateCollection();
        $merger = new StateCollectionMerger($targetCollection);

        $sourceCollection = $this->createSourceCollection();

        $this->assertFalse($targetCollection->hasState('new'));
        $this->assertFalse($targetCollection->hasState('in progress'));
        $this->assertFalse($targetCollection->hasState('done'));

        $merger->merge($sourceCollection);

        $this->assertTrue($targetCollection->hasState('new'));
        $this->assertTrue($targetCollection->hasState('in progress'));
        $this->assertTrue($targetCollection->hasState('done'));
    }

    public function testCreatedStatesAreEqualButNotTheSame()
    {
        $targetCollection = new StateCollection();
        $merger = new StateCollectionMerger($targetCollection);

        $sourceCollection = $this->createSourceCollection();
        $merger->merge($sourceCollection);

//         $this->assertEquals($sourceCollection->getState('new'),
//                         $targetCollection->getState('new'));
//         $this->assertEquals($sourceCollection->getState('in progress'),
//                         $targetCollection->getState('in progress'));
//         $this->assertEquals($sourceCollection->getState('done'),
//                         $targetCollection->getState('done'));
        
        $this->assertNotSame($sourceCollection->getState('new'),
        		$targetCollection->getState('new'));
        $this->assertNotSame($sourceCollection->getState('in progress'),
        		$targetCollection->getState('in progress'));
        $this->assertNotSame($sourceCollection->getState('done'),
        		$targetCollection->getState('done'));
    }

}
