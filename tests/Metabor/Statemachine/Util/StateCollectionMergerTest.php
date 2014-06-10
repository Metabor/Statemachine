<?php
namespace Metabor\Statemachine\Util;
use Metabor\Observer\Callback;
use Metabor\Statemachine\Condition\Tautology;
use Metabor\Statemachine\State;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Transition;

/**
 * @author Oliver Tischlinger
 *
 */
class StateCollectionMergerTest extends \PHPUnit_Framework_TestCase
{

    public function __invoke()
    {

    }

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

        $callback = new \Metabor\Callback\Callback($this);
        $observer = new Callback($callback);
        $stateNew->getEvent('start')->attach($observer);
        $stateInProcess
                ->addTransition(
                        new Transition($stateDone, null,
                                new Tautology('is finished')));

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

        /* @var $sourceState State */
        foreach ($sourceCollection->getStates() as $sourceState) {
            /* @var $targetState State */
            $targetState = $targetCollection->getState($sourceState->getName());
            $this->assertNotSame($sourceState, $targetState);
            $this
                    ->assertSameSize($sourceState->getTransitions(),
                            $targetState->getTransitions());
            $this
                    ->assertEquals($sourceState->getEventNames(),
                            $targetState->getEventNames());

            foreach ($sourceState->getEventNames() as $eventName) {
                $sourceEvent = $sourceState->getEvent($eventName);
                $targetEvent = $targetState->getEvent($eventName);
                
                $this->assertNotSame($sourceEvent, $targetEvent);
                
                $this
                        ->assertEquals($sourceEvent->getObservers(),
                                $targetEvent->getObservers());
            }
        }
    }

}
