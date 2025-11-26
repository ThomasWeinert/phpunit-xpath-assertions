<?php

declare(strict_types=1);

/*
 * This file is part of phpunit-xpath-assertions.
 *
 * (c) Thomas Weinert <thomas@weinert.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Xpath;

use ArrayAccess;
use PHPUnit\Xpath\Constraint\XpathMatch;
use PHPUnit\Xpath\Constraint\XpathCount;
use PHPUnit\Xpath\Constraint\XpathEquals;

/**
 * Xpath Constraints for assertThat() and mocks
 */
trait Constraint
{
    public static function matchesXpathExpression(string $expression, array|ArrayAccess $namespaces = []): XpathMatch
    {
        return new XpathMatch($expression, $namespaces);
    }

    public static function matchesXpathResultCount(
        int $expectedCount,
        string $expression, 
        array|ArrayAccess $namespaces = []
    ): XpathCount {
        return new XpathCount($expectedCount, $expression, $namespaces);
    }

    public static function equalToXpathResult(
        mixed $expected, 
        string $expression, 
        array|ArrayAccess $namespaces = []
    ): XpathEquals {
        return new XpathEquals($expected, $expression, $namespaces);
    }
}
