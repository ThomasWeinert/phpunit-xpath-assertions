<?php

namespace PHPUnit\Xpath\Constraint;

require_once __DIR__.'/../TestCase.php';

use PHPUnit\Xpath\TestCase;

class XpathEqualsTest extends TestCase
{
    public function testXpathEqualsExpectingTrue()
    {
        $expected = $this->getXMLDocument('<child>One</child>');
        $constraint = new XpathEquals($expected, '//child');
        $this->assertTrue(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsWithStringExpectingTrue()
    {
        $constraint = new XpathEquals('<child>One</child>', '//child');
        $this->assertTrue(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsWithNamespacesExpectingTrue()
    {
        $expected = $this->getXMLDocument('<child xmlns="urn:dummy">Two</child>');
        $constraint = new XpathEquals($expected, '//d:child[1]', ['d' => 'urn:dummy']);
        $this->assertTrue(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsExpectingFalse()
    {
        $expected = $this->getXMLDocument('<child>Two</child>');
        $constraint = new XpathEquals($expected, '//child');
        $this->assertFalse(
            $constraint->evaluate($this->getXMLDocument(), '', true)
        );
    }

    public function testXpathEqualsReturnsDescriptionWithExpression()
    {
        $constraint = new XpathEquals('<foo/>', '//child');
        $this->assertSame(
            'is equal to nodes matched by: //child',
            $constraint->toString()
        );
    }
}
