<?php
namespace PHPUnit\Xpath;

/**
 * Xpath Constraints for assertThat() and mocks
 */
trait Constraint
{
    public static function matchesXpathExpression($expression, $namespaces = []) {
        return new Constraint\XpathMatch($expression, $namespaces);
    }

    public static function matchesXpathResultCount($expectedCount, $expression, $namespaces = []) {
        return new Constraint\XpathCount($expectedCount, $expression, $namespaces);
    }

    public static function equalToXpathResult($expectedNodes, $expression, $namespaces = []) {
        return new Constraint\XpathEquals($expectedNodes, $expression, $namespaces);
    }
}
