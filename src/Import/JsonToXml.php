<?php

namespace PHPUnit\Xpath\Import;

/**
 * Import a JSON structure into DOM
 */
class JsonToXml {

    private const DEFAULT_QNAME = '_';

    private const TYPE_NULL = 'null';
    private const TYPE_BOOLEAN = 'boolean';
    private const TYPE_NUMBER = 'number';
    private const TYPE_STRING = 'string';
    private const TYPE_OBJECT = 'object';
    private const TYPE_ARRAY = 'array';

    /**
     * @var mixed
     */
    private $_json;

    public function __construct($json) {
        if (!(\is_array($json) || \is_object($json))) {
            throw new \InvalidArgumentException('Invalid $json source.');
        }
        $this->_json = $json;
    }

    /**
     * @return \DOMDocument
     */
    public function getDocument(): \DOMDocument {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->appendChild(
            $root = $document->createElement(self::DEFAULT_QNAME)
        );
        $this->transferTo($root, $this->_json);
        return $document;
    }

    /**
     * Transfer a value into a target xml element node. This sets attributes on the
     * target node and creates child elements for object and array values.
     *
     * If the current element is an object or array the method is called recursive.
     * The $recursions parameter is used to limit the recursion depth of this function.
     *
     * @param \DOMElement $target
     * @param mixed $value
     * @param int $recursions
     */
    private function transferTo(\DOMElement $target, $value, int $recursions = 100): void {
        if ($recursions < 1) {
            return;
        }
        if (\is_object($value) && !($value instanceof \stdClass)) {
            $this->transferTo($target, json_decode(json_encode($value)), $recursions);
            return;
        }
        $type = $this->getTypeFromValue($value);
        switch ($type) {
        case self::TYPE_ARRAY :
            $this->transferArrayTo($target, $value, $recursions - 1);
            break;
        case self::TYPE_OBJECT :
            $this->transferObjectTo($target, $value, $recursions - 1);
            break;
        default :
            $target->setAttribute('type', $type);
            $string = $this->getValueAsString($type, $value);
            if (\is_string($string)) {
                $target->appendChild($target->ownerDocument->createTextNode($string));
            }
        }
    }

    /**
     * Get the type from a variable value.
     *
     * @param mixed $value
     * @return string
     */
    private function getTypeFromValue($value): string {
        if (\is_array($value)) {
            if (empty($value) || array_keys($value) === range(0, \count($value) - 1)) {
                return self::TYPE_ARRAY;
            }
            return self::TYPE_OBJECT;
        }
        if (\is_object($value)) {
            return self::TYPE_OBJECT;
        }
        if (NULL === $value) {
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
     *
     * @param string $key
     * @param string $default
     * @param bool $isArrayElement
     * @return string
     */
    private function getQualifiedName(string $key, string $default, bool $isArrayElement = FALSE): string {
        if ($isArrayElement) {
            $key = $default;
        }
        $nameStartChar =
            'A-Z_a-z'.
            '\\x{C0}-\\x{D6}\\x{D8}-\\x{F6}\\x{F8}-\\x{2FF}\\x{370}-\\x{37D}'.
            '\\x{37F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}'.
            '\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}'.
            '\\x{FDF0}-\\x{FFFD}\\x{10000}-\\x{EFFFF}';
        $nameAdditionalChar =
            $nameStartChar.
            '\\.\\d\\x{B7}\\x{300}-\\x{36F}\\x{203F}-\\x{2040}';
        $result = \preg_replace(
            [
                '([^'.$nameAdditionalChar.'-]+)u',
                '(^[^'.$nameStartChar.']+)u',
            ],
            '',
            $key
        );
        return empty($result) ? $default : $result;
    }

    /**
     * @param string $type
     * @param mixed $value
     * @return NULL|string
     */
    private function getValueAsString(string $type, $value): ?string {
        switch ($type) {
        case self::TYPE_NULL :
            return NULL;
        case self::TYPE_BOOLEAN :
            return $value ? 'true' : 'false';
        default :
            return (string)$value;
        }
    }

    /**
     * Transfer an array value into a target element node. Sets the json:type attribute to 'array' and
     * creates child element nodes for each array element using the default QName.
     *
     * @param \DOMElement $target
     * @param array $value
     * @param int $recursions
     */
    private function transferArrayTo(\DOMElement $target, array $value, int $recursions): void {
        $parentName = '';
        if ($target instanceof \DOMElement) {
            $target->setAttribute( 'type', 'array');
            $parentName = $target->getAttribute('name') ?: $target->localName;
        }
        foreach ($value as $item) {
            /** @var \DOMElement $child */
            $child = $target->appendChild(
                $target->ownerDocument->createElement(
                    $this->getQualifiedName($parentName, self::DEFAULT_QNAME, TRUE)
                )
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
     *
     * @param \DOMElement $target
     * @param mixed $value
     * @param int $recursions
     */
    private function transferObjectTo(\DOMElement $target, $value, int $recursions): void {
        $properties = \is_array($value) ? $value : \get_object_vars($value);
        $target->setAttribute('type', 'object');
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
