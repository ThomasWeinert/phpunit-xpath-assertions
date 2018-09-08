<?php
/*
 * This file is part of phpunit-xpath-assertions.
 *
 * (c) Thomas Weinert <thomas@weinert.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Xpath;

/**
 * Xpath Constraints for assertThat() and mocks
 */
trait Constraint
{
    /**
     * @param string             $expression
     * @param array|\ArrayAccess $namespaces
     *
     * @return Constraint\XpathMatch
     */
    public static function matchesXpathExpression(string $expression, $namespaces = [])
    {
        return new Constraint\XpathMatch($expression, $namespaces);
    }

    /**
     * @param int                $expectedCount
     * @param string             $expression
     * @param array|\ArrayAccess $namespaces
     *
     * @return Constraint\XpathCount
     */
    public static function matchesXpathResultCount($expectedCount, string $expression, $namespaces = [])
    {
        return new Constraint\XpathCount($expectedCount, $expression, $namespaces);
    }

    /**
     * @param mixed              $expected
     * @param string             $expression
     * @param array|\ArrayAccess $namespaces
     *
     * @return Constraint\XpathEquals
     */
    public static function equalToXpathResult($expected, string $expression, $namespaces = [])
    {
        return new Constraint\XpathEquals($expected, $expression, $namespaces);
    }
}
