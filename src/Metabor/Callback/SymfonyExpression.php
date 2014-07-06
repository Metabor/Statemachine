<?php
namespace Metabor\Callback;

use MetaborStd\CallbackInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ParsedExpression;

/**
 * @author otischlinger
 *
 */
class SymfonyExpression extends Expression implements CallbackInterface
{
    /**
     *
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    /**
     * @var ParsedExpression
     */
    private $parsedExpression;

    /**
     * @var array
     */
    private $keys;

    /**
     * @param string             $expression
     * @param array              $keys
     * @param ExpressionLanguage $expressionLanguage
     */
    public function __construct($expression, array $keys = array(), ExpressionLanguage $expressionLanguage = null)
    {
        parent::__construct($expression);
        $this->keys = $keys;
        if ($expressionLanguage) {
            $this->expressionLanguage = $expressionLanguage;
        } else {
            $this->expressionLanguage = new ExpressionLanguage();
        }
    }

    /**
     * @return \Symfony\Component\ExpressionLanguage\ParsedExpression
     */
    protected function getExpression()
    {
        if (!$this->parsedExpression) {
            $this->parsedExpression = $this->expressionLanguage->parse($this, $this->keys);
        }

        return $this->parsedExpression;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
    	if(empty($this->keys)) {
    		$values = array();
    	} else {
    		$args = func_get_args();
    		$values = array_combine($this->keys, $args);
    	}

        return $this->expressionLanguage->evaluate($this->getExpression(), $values);
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }
}
