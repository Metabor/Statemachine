<?php
namespace Metabor\Statemachine\Observer;

/**
 * @author otischlinger
 */
class SubjectHasUpdated extends \SplObjectStorage implements \SplObserver
{
    /**
     * @see SplObserver::update()
     */
    public function update(\SplSubject $subject)
    {
        $this->attach($subject);
    }
}
