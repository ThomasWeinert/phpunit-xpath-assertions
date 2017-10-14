<?php
namespace PHPUnit\Xpath\Constraint;

use PHPUnit\Framework\Constraint\Constraint as PHPUnitConstraint;

/**
 * Constraint superclass for constraints that uses Xpath expressions
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
abstract class Xpath extends PHPUnitConstraint
{
    /**
    * @var string
    */
    protected $_expression;

    /**
     * @var array
     */
    private $_namespaces;

    /**
     * @param string $expression
     * @param array $namespaces
     */
    public function __construct($expression, array $namespaces = [])
    {
        parent::__construct();
        $this->_expression = $expression;
        $this->_namespaces = $namespaces;
    }

    /**
     * Evaluate the xpath expression on the given context and
     * return the result.
     *
     * @param \DOMNode $context
     * @return \DOMNodeList|bool|string|float
     */
    protected function evaluateXpathAgainst(\DOMNode $context)
    {
        $document = $context instanceof \DOMDocument ? $context : $context->ownerDocument;

        $xpath = new \DOMXPath($document);
        foreach ($this->_namespaces as $prefix=>$namespaceURI) {
            $xpath->registerNamespace($prefix, $namespaceURI);
        }
        return $xpath->evaluate($this->_expression, $context, FALSE);
    }
}
