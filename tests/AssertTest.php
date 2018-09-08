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

use PHPUnit\Framework\AssertionFailedError;

require_once __DIR__ . '/TestCase.php';

class AssertTest extends TestCase
{
    use Assert;

    public function testAssertXpathMatchSuccess()
    {
        self::assertXpathMatch('//child', $this->getXMLDocument());
    }

    public function testAssertXpathMatchFailure()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageRegExp(
            '(Failed asserting that DOMDocument Object .* matches expression: //non-existing\\.)'
        );
        self::assertXpathMatch('//non-existing', $this->getXMLDocument());
    }

    public function testAssertXpathMatchAgainstJsonExpectingSuccess()
    {
        self::assertXpathMatch('//child', ['child' => 'Text']);
    }

    public function testAssertXpathCountSuccess()
    {
        self::assertXpathCount(1, '//child', $this->getXMLDocument());
    }

    public function testAssertXpathCountFailure()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that actual node count 0 matches expected count 1.'
        );
        self::assertXpathCount(1, '//non-existing', $this->getXMLDocument());
    }

    public function testAssertXpathEqualsSuccess()
    {
        $expected = $this->getXMLDocument()->documentElement->firstChild;
        self::assertXpathEquals($expected, '//child', $this->getXMLDocument());
    }

    public function testAssertXpathEqualsWithNamespaceSuccess()
    {
        $expected = $this->getXMLDocument()->documentElement->childNodes;
        self::assertXpathEquals(
            $expected,
            '//child|//x:child',
            $this->getXMLDocument(),
            ['x' => 'urn:dummy']
        );
    }

    public function testAssertXpathEqualsWithStringExpectingSuccess()
    {
        self::assertXpathEquals('One', 'string(//child)', $this->getXMLDocument());
    }

    public function testAssertXpathEqualsFailure()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Failed asserting that two DOM structures are equal.'
        );
        $expected = $this->getXMLDocument()->documentElement->lastChild;
        self::assertXpathEquals($expected, '//child', $this->getXMLDocument());
    }
}
