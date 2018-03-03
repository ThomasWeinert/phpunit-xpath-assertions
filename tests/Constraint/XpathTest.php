<?php

namespace PHPUnit\Xpath\Constraint;

require_once __DIR__.'/../TestCase.php';

use PHPUnit\Xpath\TestCase;

class XpathTest extends TestCase
{
    public function testEvaluateXpathAgainstWithDocument() {
        $constraint = new Xpath_TestProxy(
            '//child'
        );
        $actual = $constraint->evaluateXpathAgainst($this->getXmlDocument());
        $this->assertInstanceOf(\DOMNodeList::class, $actual);
    }

    public function testEvaluateXpathAgainstWithNode() {
        $constraint = new Xpath_TestProxy(
            '//child'
        );
        $actual = $constraint->evaluateXpathAgainst($this->getXmlDocument()->documentElement);
        $this->assertInstanceOf(\DOMNodeList::class, $actual);
    }

    public function testEvaluateXpathAgainstWithNodeAndNamespace() {
        $constraint = new Xpath_TestProxy(
            '//d:child', ['d' => 'urn:dummy']
        );
        $actual = $constraint->evaluateXpathAgainst($this->getXmlDocument()->documentElement);
        $this->assertInstanceOf(\DOMNodeList::class, $actual);
        $this->assertCount(2, $actual);
    }
}

class Xpath_TestProxy extends Xpath {
    public function evaluateXpathAgainst($context) {
        return parent::evaluateXpathAgainst($context);
    }
    public function matches($other): bool {
    }
    public function toString(): string {
        return '';
    }
}
