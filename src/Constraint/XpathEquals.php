<?php
namespace PHPUnit\Xpath\Constraint;

use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Constraint that asserts that the result of an Xpath
 * expression is equal to a provided document.
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
class XpathEquals extends Xpath
{
    private $_value;

    public function __construct($value, string $expression, array $namespaces = [])
    {
        parent::__construct($expression, $namespaces);
        $this->_value = $value;
    }

    /**
     * @param mixed $other Value or object to evaluate.
     * @param string $description
     * @param bool $returnResult
     * @return bool
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        $actual = $this->evaluateXpathAgainst($other);
        try {
            if (is_scalar($actual)) {
                if (is_bool($actual)) {
                    $constraint = $this->_value ? new IsTrue() : new IsFalse();
                } else {
                    $constraint = new IsEqual($this->_value);
                }
                return $constraint->evaluate($actual, $description, $returnResult);
            }
            if (\is_string($this->_value)) {
                $this->_value = $this->loadXmlFragment($this->_value);
            }
            $expectedAsString = $this->nodesToText($this->_value);
            $actualAsString   = $this->nodesToText($actual);

            if ($expectedAsString !== $actualAsString) {
                throw new ComparisonFailure(
                    $this->_value,
                    $actual,
                    $expectedAsString,
                    $actualAsString,
                    FALSE,
                    "Failed asserting that two DOM structures are equal.\n"
                );
            }
            return TRUE;
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

    private function nodesToText($nodes) {
        $fragmentString = '';
        if ($nodes instanceof \DOMNode) {
            $fragmentString = $nodes->C14N();
        } elseif ($nodes instanceof \Traversable || \is_array($nodes)) {
            $fragmentString = '';
            foreach ($nodes as $node) {
                $fragmentString .= $node->C14N();
            }
        }
        $document = new \DOMDocument();
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
    public function toString()
    {
        return 'is equal to nodes matched by: '.$this->_expression;
    }
}
