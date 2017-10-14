<?php
namespace PHPUnit\Xpath;

/**
 * Xpath Constraints for assertThat() and mocks
 */
trait Constraint
{
    public static function matchesXpathExpression(string $expression, $namespaces = []) {
        return new Constraint\XpathMatch($expression, $namespaces);
    }

    public static function matchesXpathResultCount($expectedCount, string $expression, $namespaces = []) {
        return new Constraint\XpathCount($expectedCount, $expression, $namespaces);
    }

    public static function equalToXpathResult($expectedNodes, string $expression, $namespaces = []) {
        return new Constraint\XpathEquals($expectedNodes, $expression, $namespaces);
    }
}
