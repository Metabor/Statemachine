<?php

namespace Metabor\Statemachine\Observer;

use Metabor\Observer\Subject;

/**
 * @author otischlinger
 */
class SubjectHasUpdatedTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testCollectAllUpdatedSubjects()
    {
        $subject = new Subject();
        $observer = new SubjectHasUpdated();
        $subject->attach($observer);
        $this->assertNotContains($subject, $observer);
        $subject->notify();
        $this->assertContains($subject, $observer);
    }
}
