<?php

require_once __DIR__.'/../vendor/autoload.php';

class MyProjectExampleTest extends \PHPUnit\Framework\TestCase
{
    use \PHPUnit\Xpath\Constraint;

    public function testChildElementExistsInDocument()
    {
        $document = new \DOMDocument();
        $document->loadXML('<root><child>TEXT</child></root>');

        self::assertThat(
            $document,
            self::matchesXpathExpression('//child')
        );
    }

    public function testChildElementExistsOnTimeInDocument()
    {
        $document = new \DOMDocument();
        $document->loadXML('<root><child>TEXT</child></root>');

        self::assertThat(
            $document,
            self::matchesXpathResultCount(1, '//child')
        );
    }

    public function testChildWithNamespaceElementExistsTwoTimesInDocument()
    {
        $document = new \DOMDocument();
        $document->loadXML(
            '<example:root xmlns:example="urn:example">
            <example:child>TEXT</example:child>
            <example:child>TEXT</example:child>
            </example:root>'
        );

        self::assertThat(
            $document,
            self::matchesXpathResultCount(2,'//e:child', ['e' => 'urn:example'])
        );
    }

    public function testCompareChildElementFromDocument()
    {
        $document = new \DOMDocument();
        $document->loadXML('<root><child>TEXT</child></root>');

        self::assertThat(
            $document,
            self::equalToXpathResult(
                '<child>TEXT</child>',
                '//child'
            )
        );
    }
}
