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

use DOMDocument;
use DOMElement;
use stdClass;

/**
 * Import a JSON structure into DOM
 */
class JsonToXml
{
    private const DEFAULT_QNAME = '_';

    private const TYPE_NULL    = 'null';
    private const TYPE_BOOLEAN = 'boolean';
    private const TYPE_NUMBER  = 'number';
    private const TYPE_STRING  = 'string';
    private const TYPE_OBJECT  = 'object';
    private const TYPE_ARRAY   = 'array';

    public function __construct(private object|array $json, private int $maxRecursions = 100)
    {
        $this->maxRecursions = \max(0, $maxRecursions);
    }

    public function getDocument(): DOMDocument
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $root = $document->createElement(self::DEFAULT_QNAME);
        $document->appendChild($root);
        $this->transferTo($root, $this->json, $this->maxRecursions);

        return $document;
    }

    /**
     * Transfer a value into a target xml element node. This sets attributes on the
     * target node and creates child elements for object and array values.
     *
     * If the current element is an object or array the method is called recursive.
     * The $recursions parameter is used to limit the recursion depth of this function.
     */
    private function transferTo(DOMElement $target, mixed $value, int $recursions = 100): void
    {
        if (\is_object($value) && !($value instanceof stdClass)) {
            $this->transferTo($target, \json_decode(\json_encode($value)), $recursions);
            return;
        }

        $type = $this->getTypeFromValue($value);
        $target->setAttribute('type', $type);
        $isComplex = ($type === self::TYPE_ARRAY || $type === self::TYPE_OBJECT);
        if ($isComplex) {
            if ($recursions < 1) {
                return;
            }
            if ($type === self::TYPE_ARRAY) {
                $this->transferArrayTo($target, $value, $recursions - 1);
            } else {
                $this->transferObjectTo($target, $value, $recursions - 1);
            }

            return;
        }
        $string = $this->getValueAsString($type, $value);
        if (\is_string($string)) {
            $target->appendChild($target->ownerDocument->createTextNode($string));
        }
    }

    /**
     * Get the type from a variable value.
     */
    private function getTypeFromValue(mixed $value): string
    {
        if (\is_array($value)) {
            if (empty($value) || \array_keys($value) === \range(0, \count($value) - 1)) {
                return self::TYPE_ARRAY;
            }

            return self::TYPE_OBJECT;
        }

        if (\is_object($value)) {
            return self::TYPE_OBJECT;
        }

        if (null === $value) {
            return self::TYPE_NULL;
        }

        if (\is_bool($value)) {
            return self::TYPE_BOOLEAN;
        }

        if (\is_int($value) || \is_float($value)) {
            return self::TYPE_NUMBER;
        }

        return self::TYPE_STRING;
    }

    /**
     * Get a valid qualified name (tag name) using the property name/key.
     */
    private function getQualifiedName(string $key, string $default): string
    {
        $nameStartChar =
            'A-Z_a-z' .
            '\\x{C0}-\\x{D6}\\x{D8}-\\x{F6}\\x{F8}-\\x{2FF}\\x{370}-\\x{37D}' .
            '\\x{37F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}' .
            '\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}' .
            '\\x{FDF0}-\\x{FFFD}\\x{10000}-\\x{EFFFF}';
        $nameAdditionalChar =
            $nameStartChar .
            '\\.\\d\\x{B7}\\x{300}-\\x{36F}\\x{203F}-\\x{2040}';
        $result = \preg_replace(
            [
                '([^' . $nameAdditionalChar . '-]+)u',
                '(^[^' . $nameStartChar . ']+)u',
            ],
            '',
            $key
        );

        return empty($result) ? $default : $result;
    }

    private function getValueAsString(string $type, mixed $value): ?string
    {
        switch ($type) {
        case self::TYPE_NULL :
            return null;
        case self::TYPE_BOOLEAN :
            return $value ? 'true' : 'false';
        default :
            return (string) $value;
        }
    }

    /**
     * Transfer an array value into a target element node. Sets the json:type attribute to 'array' and
     * creates child element nodes for each array element using the default QName.
     */
    private function transferArrayTo(\DOMElement $target, array $value, int $recursions): void
    {
        foreach ($value as $item) {
            /** @var \DOMElement $child */
            $child = $target->appendChild(
                $target->ownerDocument->createElement(self::DEFAULT_QNAME)
            );
            $this->transferTo($child, $item, $recursions);
        }
    }

    /**
     * Transfer an object value into a target element node. If the object has no properties,
     * the json:type attribute is always set to 'object'. If verbose is not set the json:type attribute will
     * be omitted if the object value has properties.
     *
     * The method creates child nodes for each property. The property name will be normalized to a valid NCName.
     * If the normalized NCName is different from the property name or verbose is TRUE, a json:name attribute
     * with the property name will be added.
     */
    private function transferObjectTo(DOMElement $target, mixed $value, int $recursions): void
    {
        $properties = \is_array($value) ? $value : \get_object_vars($value);
        foreach ($properties as $property => $item) {
            /** @var \DOMElement $child */
            $tagName = $this->getQualifiedName($property, self::DEFAULT_QNAME);
            $target->appendChild(
                $child = $target->ownerDocument->createElement($tagName)
            );
            $child->setAttribute('name', $property);
            $this->transferTo($child, $item, $recursions);
        }
    }
}
