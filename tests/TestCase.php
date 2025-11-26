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

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use DOMDocument;

class TestCase extends PHPUnitTestCase
{
    const XML =
        '<root>
            <child>One</child>
            <child xmlns="urn:dummy">Two</child>
            <ns1:child xmlns:ns1="urn:dummy">Three</ns1:child>
        </root>';

    public function getXMLDocument($xml = self::XML): DOMDocument
    {
        $document                     = new \DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->loadXML($xml);

        return $document;
    }
}
