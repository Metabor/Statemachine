<?php
namespace Metabor\Statemachine\Observer;

use Metabor\Statemachine\Statemachine;
use Metabor\StringConverter;
use MetaborStd\Statemachine\StatemachineInterface;
use MetaborStd\Statemachine\TransitionInterface;
use MetaborStd\StringConverterInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @author otischlinger
 */
class TransitionLogger implements \SplObserver
{
    const CONTEXT_SUBJECT = 'subject';
    const CONTEXT_CURRENT_STATE = 'currentState';
    const CONTEXT_LAST_STATE = 'lastState';
    const CONTEXT_TRANSITION = 'transition';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $loggerLevel;

    /**
     * @var StringConverterInterface
     */
    private $stringConverter;

    /**
     * @param LoggerInterface $logger
     * @param string $loggerLevel
     * @param StringConverterInterface $stringConverter
     */
    public function __construct(LoggerInterface $logger, $loggerLevel = LogLevel::INFO, StringConverterInterface $stringConverter = null)
    {
        $this->logger = $logger;
        $this->loggerLevel = $loggerLevel;
        if ($stringConverter) {
            $this->stringConverter = $stringConverter;
        } else {
            $this->stringConverter = new StringConverter();
        }
    }

    /**
     * @param StatemachineInterface $stateMachine
     *
     * @return array
     */
    protected function createLoggerContext(StatemachineInterface $stateMachine)
    {
        $context = array();
        $context[self::CONTEXT_SUBJECT] = $stateMachine->getSubject();
        $context[self::CONTEXT_CURRENT_STATE] = $stateMachine->getCurrentState();
        if ($stateMachine instanceof Statemachine) {
            $context[self::CONTEXT_LAST_STATE] = $stateMachine->getLastState();
            $context[self::CONTEXT_TRANSITION] = $stateMachine->getSelectedTransition();
        }

        return $context;
    }

    /**
     * @param array $context
     *
     * @return string
     */
    protected function createLoggerMessage(array $context)
    {
        $message = 'Transition';

        if (isset($context[self::CONTEXT_SUBJECT])) {
            $message.= ' for "' . $this->stringConverter->convertToString($context[self::CONTEXT_SUBJECT]) . '"';
        }

        if (isset($context[self::CONTEXT_LAST_STATE])) {
            $message.= ' from "' . $this->stringConverter->convertToString($context[self::CONTEXT_LAST_STATE]) . '"';
        }

        if (isset($context[self::CONTEXT_CURRENT_STATE])) {
            $message.= ' to "' . $this->stringConverter->convertToString($context[self::CONTEXT_CURRENT_STATE]) . '"';
        }

        if (isset($context[self::CONTEXT_TRANSITION])) {
            /* @var $transition TransitionInterface */
            $transition = $context[self::CONTEXT_TRANSITION];
            $eventName = $transition->getEventName();
            $condition = $transition->getConditionName();

            if ($eventName || $condition) {
                $message.= ' with';
                if ($eventName) {
                    $message.= ' event "' . $eventName . '"';
                }
                if ($eventName) {
                    $message.= ' condition "' . $condition . '"';
                }
            }
        }

        return $message;
    }

    /**
     * @see SplObserver::update()
     */
    public function update(\SplSubject $stateMachine)
    {
        if ($stateMachine instanceof StatemachineInterface) {
            $context = $this->createLoggerContext($stateMachine);
            $message = $this->createLoggerMessage($context);
            $this->logger->log($this->loggerLevel, $message, $context);
        }
    }
}
