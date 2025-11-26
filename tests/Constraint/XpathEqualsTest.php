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

use PHPUnit\Xpath\TestCase;

class XpathEqualsTest extends TestCase
{
    public function testXpathEqualsExpectingTrue(): void
    {
        $expected   = $this->getXMLDocument('<child>One</child>');
        $constraint = new XpathEquals($expected, '//child');
        $this->assertTrue(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsWithStringExpectingTrue(): void
    {
        $constraint = new XpathEquals('<child>One</child>', '//child');
        $this->assertTrue(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsWithNamespacesExpectingTrue(): void
    {
        $expected   = $this->getXMLDocument('<child xmlns="urn:dummy">Two</child>');
        $constraint = new XpathEquals($expected, '//d:child[1]', ['d' => 'urn:dummy']);
        $this->assertTrue(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsExpectingFalse(): void
    {
        $expected   = $this->getXMLDocument('<child>Two</child>');
        $constraint = new XpathEquals($expected, '//child');
        $this->assertFalse(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsReturnsDescriptionWithExpression(): void
    {
        $constraint = new XpathEquals('<foo/>', '//child');
        $this->assertSame(
            'is equal to nodes matched by: //child',
            $constraint->toString()
        );
    }
}
