<?php
namespace PHPUnit\Xpath;
use PHPUnit\Util\InvalidArgumentHelper;
use PHPUnit\Xpath\Constraint\XpathCount;
use PHPUnit\Xpath\Constraint\XpathEquals;
use PHPUnit\Xpath\Constraint\XpathMatch;


/**
 * Trait that with Xpath based assertions
 */
trait Assert
{
    /**
     * Asserts that DOM node matches a specified Xpath expression.
     *
     * @param string $expression
     * @param \DOMNode $node
     * @param array|\ArrayAccess $namespaces
     * @param string $message
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertXpathMatch(string $expression, \DOMNode $node, $namespaces = [], $message = '')
    {

        if (!(\is_array($namespaces) || $namespaces instanceof \ArrayAccess)) {
            throw InvalidArgumentHelper::factory(
                3,
                'array or ArrayAccess'
            );
        }

        $constraint = new XpathMatch($expression, $namespaces);
        static::assertThat($node, $constraint, $message);
    }

    /**
     * Asserts that DOM node matches a specified Xpath expression.
     *
     * @param int|string $expected
     * @param string $expression
     * @param \DOMNode $node
     * @param array|\ArrayAccess $namespaces
     * @param string $message
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertXpathCount($expected, string $expression, \DOMNode $node, $namespaces = [], $message = '')
    {
        if (!(\is_int($expected) || \is_string($expected))) {
            throw InvalidArgumentHelper::factory(
                1,
                'integer or string'
            );
        }

        if (!(\is_array($namespaces) || $namespaces instanceof \ArrayAccess)) {
            throw InvalidArgumentHelper::factory(
                4,
                'array or ArrayAccess'
            );
        }

        $constraint = new XpathCount($expected, $expression, $namespaces);
        static::assertThat($node, $constraint, $message);
    }

    /**
     * Asserts that DOM nodes returned by an Xpath expresion are equal to the expected
     *
     * @param mixed $expected
     * @param string $expression
     * @param \DOMNode $node
     * @param array|\ArrayAccess $namespaces
     * @param string $message
     * @throws \PHPUnit\Framework\Exception
     */
    public static function assertXpathEquals($expected, string $expression, \DOMNode $node, $namespaces = [], $message = '')
    {
        if (!(\is_array($namespaces) || $namespaces instanceof \ArrayAccess)) {
            throw InvalidArgumentHelper::factory(
                4,
                'array or ArrayAccess'
            );
        }

        $constraint = new XpathEquals($expected, $expression, $namespaces);
        static::assertThat($node, $constraint, $message);
    }
}
