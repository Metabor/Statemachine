<?php

namespace Metabor\Statemachine\Observer;

use MetaborStd\NamedInterface;
use MetaborStd\Statemachine\StatemachineInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @author Oliver Tischlinger
 */
class TransitionLogger implements \SplObserver
{
    private LoggerInterface $logger;
    private string $logLevel;

    public function __construct(LoggerInterface $logger, string $logLevel = LogLevel::INFO)
    {
        $this->logger = $logger;
        $this->logLevel = $logLevel;
    }

    protected function buildLogMessage(StatemachineInterface $statemachine): string
    {
        $message = 'Transition';
        $subject = $statemachine->getSubject();
        if ($subject instanceof NamedInterface) {
            $message .= ' for "' . $subject->getName() . '"';
        }
        $message .= ' to "' . $statemachine->getCurrentState()->getName() . '"';
        return $message;
    }

    protected function buildLogContext(StatemachineInterface $statemachine): array
    {
        $subject = $statemachine->getSubject();
        $lastState = method_exists($statemachine, 'getLastState') ? $statemachine->getLastState() : null;
        $transition = method_exists($statemachine, 'getSelectedTransition') ? $statemachine->getSelectedTransition() : null;

        return [
            'subject'      => $subject,
            'currentState' => $statemachine->getCurrentState(),
            'lastState'    => $lastState,
            'transition'   => $transition,
        ];
    }

    public function update(\SplSubject $subject): void
    {
        if ($subject instanceof StatemachineInterface) {
            $this->logger->log(
                $this->logLevel,
                $this->buildLogMessage($subject),
                $this->buildLogContext($subject)
            );
        }
    }
}
