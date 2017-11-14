<?php

namespace PHPUnit\Xpath\Import;

require_once __DIR__.'/../TestCase.php';

use PHPUnit\Xpath\TestCase;

class JsonToXmlTest extends TestCase
{
    /**
     * @param string $xml
     * @param \stdClass|array|\JsonSerializable $json
     * @dataProvider provideJsonToXmlPairs
     */
    public function testImport(string $xml, $json) {
        $import = new JsonToXml(json_decode($json));
        $this->assertXmlStringEqualsXmlString($xml, $import->getDocument()->saveXML());
    }

    public static function provideJsonToXmlPairs() {
        return [
            [
                '<_ type="object"><foo name="foo" type="string">bar</foo></_>',
                '{"foo":"bar"}'
            ],
            [
                '<_ type="object"><foo name="foo" type="number">42</foo></_>',
                '{"foo":42}'
            ]
        ];
    }

}
