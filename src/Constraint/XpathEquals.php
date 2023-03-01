<?php
/*
 * This file is part of phpunit-xpath-assertions.
 *
 * (c) Thomas Weinert <thomas@weinert.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Xpath\Constraint;

use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Xpath\Import\JsonToXml;
use SebastianBergmann\Comparator\ComparisonFailure;
use ReflectionClass;

/**
 * Constraint that asserts that the result of an Xpath
 * expression is equal to a provided document.
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
class XpathEquals extends Xpath
{
    private $_value;

    /**
     * @var int
     * @todo REMOVE when dropping support for PHP 7.4 and sebastianbergmann/comparator v4
     */
    private static $_comparionFailureParams = 0;

    public function __construct($value, string $expression, array $namespaces = [])
    {
        parent::__construct($expression, $namespaces);
        $this->_value = $value;

        if (static::$_comparionFailureParams === 0) {
            static::$_comparionFailureParams = (new ReflectionClass(ComparisonFailure::class))
                ->getConstructor()
                ->getNumberOfParameters();
        }
    }

    /**
     * @param mixed  $other        Value or object to evaluate.
     * @param string $description
     * @param bool   $returnResult
     *
     * @return bool
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        $actual = $this->evaluateXpathAgainst($other);
        try {
            if (\is_scalar($actual)) {
                if (\is_bool($actual)) {
                    $constraint = $this->_value ? new IsTrue() : new IsFalse();
                } else {
                    $constraint = new IsEqual($this->_value);
                }

                return $constraint->evaluate($actual, $description, $returnResult);
            }
            if (\is_string($this->_value)) {
                $this->_value = $this->loadXmlFragment($this->_value);
            } elseif (!$this->isNodeOrNodeList($this->_value)) {
                $importer     = new JsonToXml($this->_value);
                $this->_value = $importer->getDocument()->documentElement->childNodes;
            }
            $expectedAsString = $this->nodesToText($this->_value);
            $actualAsString   = $this->nodesToText($actual);

            if ($expectedAsString !== $actualAsString) {
                // @todo REMOVE when dropping support for PHP 7.4 and sebastianbergmann/comparator v4
                if (static::$_comparionFailureParams === 6) {
                    throw new ComparisonFailure(
                        $this->_value,
                        $actual,
                        $expectedAsString,
                        $actualAsString,
                        false,
                        "Failed asserting that two DOM structures are equal.\n"
                    );
                }

                throw new ComparisonFailure(
                    $this->_value,
                    $actual,
                    $expectedAsString,
                    $actualAsString,
                    "Failed asserting that two DOM structures are equal.\n"
                );
            }

            return true;
        } catch (ComparisonFailure $f) {
            if ($returnResult) {
                return false;
            }

            throw new ExpectationFailedException(
                \trim($description . "\n" . $f->getMessage()),
                $f
            );
        }
    }

    private function isNodeOrNodeList($value)
    {
        return
            ($this->_value instanceof \DOMNodeList || $this->_value instanceof \DOMNode) ||
            (\is_array($this->_value) && isset($this->_value[0]) && $this->_value[0] instanceof \DOMNode);
    }

    private function nodesToText($nodes)
    {
        $fragmentString = '';
        if ($nodes instanceof \DOMNode) {
            $fragmentString = $nodes->C14N();
        } elseif ($nodes instanceof \Traversable || \is_array($nodes)) {
            $fragmentString = '';
            foreach ($nodes as $node) {
                $fragmentString .= $node->C14N();
            }
        }
        $document               = new \DOMDocument();
        $document->formatOutput = true;
        $document->normalizeDocument();
        $fragment = $document->createDocumentFragment();
        $fragment->appendXML($fragmentString);

        return $document->saveXML($fragment);
    }

    private function loadXmlFragment($xmlString)
    {
        $document = new \DOMDocument();
        $fragment = $document->createDocumentFragment();
        $fragment->appendXML($xmlString);
        $document->appendChild($fragment);

        return $document;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'is equal to nodes matched by: ' . $this->_expression;
    }
}
