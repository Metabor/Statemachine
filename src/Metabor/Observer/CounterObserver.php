<?php
namespace Metabor\Observer;

/**
 * @author otischlinger
 */
class CounterObserver implements \SplObserver
{
    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @see SplObserver::update()
     */
    public function update(\SplSubject $subject)
    {
        $this->count++;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * 
     */
    public function resetCount()
    {
        $this->count = 0;
    }
}
