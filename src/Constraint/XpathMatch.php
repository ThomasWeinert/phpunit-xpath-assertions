<?php
namespace PHPUnit\Xpath\Constraint;

/**
 * Constraint that asserts that the result of an Xpath
 * expression is TRUE and not empty.
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
class XpathMatch extends Xpath
{

    /**
     * @param mixed $other Value or object to evaluate.
     * @return bool
     */
    protected function matches($other): bool {
        $actual = $this->evaluateXpathAgainst($other);
        if ($actual instanceof \DOMNodeList)
        {
            return $actual->length > 0;
        }
        return (bool)$actual;
    }

    /**
     * @return string
     */
    public function toString(): string {
        return \sprintf(
            'matches expression: %s',
            $this->_expression
        );
    }
}
