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

use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use JsonSerializable;
use PHPUnit\Framework\Constraint\Constraint as PHPUnitConstraint;
use PHPUnit\Framework\Exception as PHPUnitException;
use PHPUnit\Util\InvalidArgumentHelper;
use PHPUnit\Xpath\Import\JsonToXml;
use stdClass;

use function is_array;

/**
 * Constraint superclass for constraints that uses Xpath expressions
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
abstract class AbstractXpath extends PHPUnitConstraint
{
    public function __construct(protected string $expression, private array $namespaces = [])
    {
    }

    /**
     * Evaluate the xpath expression on the given context and
     * return the result.
     */
    protected function evaluateXpathAgainst(mixed $context): DOMNodeList|bool|string|float
    {
        if ($context instanceof DOMNode) {
            $document = $context instanceof DOMDocument ? $context : $context->ownerDocument;
        } else {
            $importer = new JsonToXml($context);
            $document = $importer->getDocument();
            $context  = $document->documentElement;
        }

        $xpath = new DOMXPath($document);
        foreach ($this->namespaces as $prefix => $namespaceUri) {
            $xpath->registerNamespace($prefix, $namespaceUri);
        }

        return $xpath->evaluate($this->expression, $context, false);
    }

    /**
     * @throws PHPUnitException
     */
    public static function isValidContext(mixed $context, int $argument): void
    {
        if (
            ! (
                $context instanceof DOMNode ||
                is_array($context) ||
                $context instanceof stdClass ||
                $context instanceof JsonSerializable
            )
        ) {
            throw InvalidArgumentHelper::factory(
                $argument,
                '\\DOMNode, array, \\stdClass or \\JsonSerializable'
            );
        }
    }
}
