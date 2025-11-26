<?php

declare(strict_types=1);

/*
 * This file is part of phpunit-xpath-assertions.
 *
 * (c) Thomas Weinert <thomas@weinert.info>
 * (c) Fabian Grutschus <github@lubyte.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Xpath\Constraint;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Xpath\TestCase;

final class XpathMatchTest extends TestCase
{
    /**
     * @dataProvider provideMatchingExpressions
     */
    #[DataProvider('provideMatchingExpressions')]
    public function testXpathMatchExpectTrue(string $expression, array $namespaces = []): void
    {
        $constraint = new XpathMatch($expression, $namespaces);
        $this->assertTrue($constraint->evaluate($this->getXMLDocument(), '', true));
    }

    public static function provideMatchingExpressions(): array
    {
        return [
            ['expression' => '/root', 'namespaces' => []],
            ['expression' => '//child', 'namespaces' => []],
            ['expression' => '//test:child', 'namespaces' => ['test' => 'urn:dummy']],
            ['expression' => '//child = "One"', 'namespaces' => []],
            ['expression' => 'string(//child)', 'namespaces' => []],
            ['expression' => 'count(//test:child)', 'namespaces' => ['test' => 'urn:dummy']],
        ];
    }

    /**
     * @dataProvider provideNonMatchingExpressions
     */
    #[DataProvider('provideNonMatchingExpressions')]
    public function testXpathMatchExpectFalse(string $expression, array $namespaces = []): void
    {
        $constraint = new XpathMatch($expression, $namespaces);
        $this->assertFalse($constraint->evaluate($this->getXMLDocument(), '', true));
    }

    public static function provideNonMatchingExpressions(): array
    {
        return [
            ['expression' => '/child', 'namespaces' => []],
            ['expression' => '//non-existing', 'namespaces' => []],
            ['expression' => '//test:child', 'namespaces' => ['test' => 'urn:non-existing']],
            ['expression' => '//child = "NON-EXISTING"', 'namespaces' => []],
            ['expression' => 'string(//non-existing)', 'namespaces' => []],
            ['expression' => 'count(//test:child)', 'namespaces' => ['test' => 'urn:non-existing']],
        ];
    }
}
