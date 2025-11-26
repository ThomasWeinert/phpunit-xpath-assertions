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

namespace PHPUnit\Xpath\Import;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Xpath\JsonSerializableExample;
use PHPUnit\Xpath\TestCase;

use function json_decode;

class JsonToXmlTest extends TestCase
{
    /**
     * @dataProvider provideJsonToXmlPairs
     */
    #[DataProvider('provideJsonToXmlPairs')]
    public function testImport(string $xml, string $json): void
    {
        $import = new JsonToXml(json_decode($json));
        $this->assertXmlStringEqualsXmlString($xml, $import->getDocument()->saveXML());
    }

    public static function provideJsonToXmlPairs(): array
    {
        return [
            'string'        => [
                '<_ type="object"><foo name="foo" type="string">bar</foo></_>',
                '{"foo":"bar"}',
            ],
            'number'        => [
                '<_ type="object"><foo name="foo" type="number">42</foo></_>',
                '{"foo":42}',
            ],
            'boolean true'  => [
                '<_ type="object"><foo name="foo" type="boolean">true</foo></_>',
                '{"foo":true}',
            ],
            'boolean false' => [
                '<_ type="object"><foo name="foo" type="boolean">false</foo></_>',
                '{"foo":false}',
            ],
            'null'          => [
                '<_ type="object"><foo name="foo" type="null"></foo></_>',
                '{"foo":null}',
            ],
            'empty string'  => [
                '<_ type="object"><foo name="foo" type="string"></foo></_>',
                '{"foo":""}',
            ],
            'empty object'  => [
                '<_ type="object"><foo name="foo" type="object"></foo></_>',
                '{"foo":{}}',
            ],
            'array'         => [
                '<_ type="object">'
                . '  <foo name="foo" type="array">'
                . '    <_ type="number">21</_>'
                . '    <_ type="number">42</_>'
                . '  </foo>'
                . '</_>',
                '{"foo": [21, 42] }',
            ],
        ];
    }

    public function testImportWithJsonSerialzable(): void
    {
        $data   = new JsonSerializableExample(
            [
                'string' => 'hello',
                'number' => 42,
            ]
        );
        $import = new JsonToXml($data);
        $this->assertXmlStringEqualsXmlString(
            '<_ type="object">'
            . '  <string name="string" type="string">hello</string>'
            . '  <number name="number" type="number">42</number>'
            . '</_>',
            $import->getDocument()->saveXML()
        );
    }

    public function testImportWithRecursionLimit(): void
    {
        $data = new JsonSerializableExample(
            [
                'level' => 1,
                'child' => [
                    'level' => 2,
                    'child' => [
                        'level' => 3,
                        'child' => [
                            'level' => 4,
                        ],
                    ],
                ],
            ]
        );

        $import = new JsonToXml($data, 2);
        $this->assertXmlStringEqualsXmlString(
            '<_ type="object">'
            . '  <level name="level" type="number">1</level>'
            . '  <child name="child" type="object">'
            . '    <level name="level" type="number">2</level>'
            . '    <child name="child" type="object"/>'
            . '  </child>'
            . '</_>',
            $import->getDocument()->saveXML()
        );
    }
}
