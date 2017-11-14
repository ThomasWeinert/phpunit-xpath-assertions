<?php
namespace PHPUnit\Xpath\Constraint;

use PHPUnit\Framework\Constraint\Constraint as PHPUnitConstraint;
use PHPUnit\Util\InvalidArgumentHelper;
use PHPUnit\Xpath\Import\JsonToXml;

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
     * @param mixed $context
     * @return \DOMNodeList|bool|string|float
     */
    protected function evaluateXpathAgainst($context)
    {
        if ($context instanceof \DOMNode) {
            $document = $context instanceof \DOMDocument ? $context : $context->ownerDocument;
        } else {
            $importer = new JsonToXml($context);
            $document = $importer->getDocument();
            $context = $document->documentElement;
        }

        $xpath = new \DOMXPath($document);
        foreach ($this->_namespaces as $prefix=>$namespaceURI) {
            $xpath->registerNamespace($prefix, $namespaceURI);
        }
        return $xpath->evaluate($this->_expression, $context, FALSE);
    }

    /**
     * @param mixed $context
     * @param int $argument
     * @throws \PHPUnit\Framework\Exception
     */
    public static function isValidContext($context, int $argument) {
        if (
        !(
            $context instanceof \DOMNode ||
            \is_array($context) ||
            $context instanceof \stdClass ||
            $context instanceof \JsonSerializable
        )
        ) {
            throw InvalidArgumentHelper::factory(
                $argument,
                '\\DOMNode, array, \\stdClass or \\JsonSerializable'
            );
        }
    }
}
