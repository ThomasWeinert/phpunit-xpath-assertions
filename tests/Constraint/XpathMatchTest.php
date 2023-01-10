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

require_once __DIR__ . '/../TestCase.php';

use PHPUnit\Xpath\TestCase;

class XpathMatchTest extends TestCase
{
    /**
     * @dataProvider provideMatchingExpressions
     *
     * @param string $expression
     * @param array  $namespaces
     */
    public function testXpathMatchExpectTrue(string $expression, array $namespaces = [])
    {
        $constraint = new XpathMatch($expression, $namespaces);
        $this->assertTrue($constraint->evaluate($this->getXMLDocument(), '', true));
    }

    public static function provideMatchingExpressions()
    {
        return [
            ['/root'],
            ['//child'],
            ['//test:child', ['test' => 'urn:dummy']],
            ['//child = "One"'],
            ['string(//child)'],
            ['count(//test:child)', ['test' => 'urn:dummy']],
        ];
    }
    /**
     * @dataProvider provideNonMatchingExpressions
     *
     * @param string $expression
     * @param array  $namespaces
     */
    public function testXpathMatchExpectFalse(string $expression, array $namespaces = [])
    {
        $constraint = new XpathMatch($expression, $namespaces);
        $this->assertFalse($constraint->evaluate($this->getXMLDocument(), '', true));
    }

    public static function provideNonMatchingExpressions()
    {
        return [
            ['/child'],
            ['//non-existing'],
            ['//test:child', ['test' => 'urn:non-existing']],
            ['//child = "NON-EXISTING"'],
            ['string(//non-existing)'],
            ['count(//test:child)', ['test' => 'urn:non-existing']],
        ];
    }
}
