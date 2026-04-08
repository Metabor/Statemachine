<?php

namespace Metabor\Callback;

use MetaborStd\CallbackInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParsedExpression;

/**
 * @author Oliver Tischlinger
 */
class SymfonyExpression implements CallbackInterface
{
    private ExpressionLanguage $expressionLanguage;
    private ParsedExpression $parsedExpression;
    private array $keys;

    public function __construct(string $expression, array $keys = [], ?ExpressionLanguage $expressionLanguage = null)
    {
        if ($expressionLanguage) {
            $this->expressionLanguage = $expressionLanguage;
        } else {
            $this->expressionLanguage = new ExpressionLanguage();
        }
        $this->keys = $keys;
        $this->parsedExpression = $this->expressionLanguage->parse($expression, $keys);
    }

    public function getExpression(): ParsedExpression
    {
        return $this->parsedExpression;
    }

    public function __invoke(mixed ...$args): mixed
    {
        $values = array_combine($this->keys, $args);
        return $this->expressionLanguage->evaluate($this->parsedExpression, $values);
    }

    public function getKeys(): array
    {
        return $this->keys;
    }

    public function __toString(): string
    {
        return (string) $this->parsedExpression;
    }
}
