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

class ConstraintTraitTest extends TestCase
{
    use ConstraintTrait;

    public function testAssertXpathMatchSuccess(): void
    {
        self::assertThat(
            $this->getXMLDocument(),
            self::matchesXpathExpression('//child')
        );
    }

    public function testAssertXpathCountSuccess(): void
    {
        self::assertThat(
            $this->getXMLDocument(),
            self::matchesXpathResultCount(1, '//child')
        );
    }

    public function testAssertXpathEqualsSuccess(): void
    {
        $expected = $this->getXMLDocument()->documentElement->firstChild;
        self::assertThat(
            $this->getXMLDocument(),
            self::equalToXpathResult($expected, '//child')
        );
    }

    public function testAssertXpathEqualsWithNamespaceSuccess(): void
    {
        $expected = $this->getXMLDocument()->documentElement->childNodes;
        self::assertThat(
            $this->getXMLDocument(),
            self::equalToXpathResult($expected, '//child|//x:child', ['x' => 'urn:dummy'])
        );
    }

    public function testAssertXpathEqualsWithStringSuccess(): void
    {
        self::assertThat(
            $this->getXMLDocument(),
            self::equalToXpathResult('One', 'string(//child)')
        );
    }

    public function testAssertXpathEqualsWithTrueSuccess(): void
    {
        self::assertThat(
            $this->getXMLDocument(),
            self::equalToXpathResult(true, 'count(//child) > 0')
        );
    }

    public function testAssertXpathEqualsWithFalseSuccess(): void
    {
        self::assertThat(
            $this->getXMLDocument(),
            self::equalToXpathResult(false, 'count(//child) > 42')
        );
    }

    public function testAssertXpathEqualsWithTrueFailure(): void
    {
        self::assertThat(
            $this->getXMLDocument(),
            self::logicalNot(
                self::equalToXpathResult(true, 'count(//child) > 42')
            )
        );
    }
}
