<?php
namespace PHPUnit\Xpath\Constraint;

/**
 * Constraint that asserts that the result of an Xpath
 * expression is a node list with the specific count of nodes.
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
class XpathCount extends Xpath
{
    private $_expectedCount;
    private $_actualCount = 0;

    public function __construct(int $expectedCount, string $expression, array $namespaces = [])
    {
        parent::__construct($expression, $namespaces);
        $this->_expectedCount = $expectedCount;
    }

    /**
     * @param mixed $other Value or object to evaluate.
     * @return bool
     */
    protected function matches($other): bool {
        $actual = $this->evaluateXpathAgainst($other);
        $this->_actualCount = $actual->length;
        return $this->_actualCount === $this->_expectedCount;
    }

    protected function failureDescription($other)
    {
        return \sprintf(
            'actual node count %d matches expected count %d',
            $this->_actualCount,
            $this->_expectedCount
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return \sprintf(
            'count matches %d',
            $this->_expectedCount
        );
    }
}
