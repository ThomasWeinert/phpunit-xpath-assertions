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

namespace PHPUnit\Xpath\Constraint;

use PHPUnit\Xpath\TestCase;
use PHPUnit\Xpath\TestProxy;
use DOMNodeList;

class XpathTest extends TestCase
{
    public function testEvaluateXpathAgainstWithDocument(): void
    {
        $constraint = new TestProxy('//child');
        $actual = $constraint->proxyEvaluateXpathAgainst($this->getXMLDocument());
        $this->assertInstanceOf(DOMNodeList::class, $actual);
    }

    public function testEvaluateXpathAgainstWithNode(): void
    {
        $constraint = new TestProxy('//child');
        $actual = $constraint->proxyEvaluateXpathAgainst($this->getXMLDocument()->documentElement);
        $this->assertInstanceOf(DOMNodeList::class, $actual);
    }

    public function testEvaluateXpathAgainstWithNodeAndNamespace(): void
    {
        $constraint = new TestProxy('//d:child', ['d' => 'urn:dummy']);
        $actual = $constraint->proxyEvaluateXpathAgainst($this->getXMLDocument()->documentElement);
        $this->assertInstanceOf(DOMNodeList::class, $actual);
        $this->assertCount(2, $actual);
    }
}
