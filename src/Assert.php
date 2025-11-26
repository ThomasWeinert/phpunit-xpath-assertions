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

use PHPUnit\Util\InvalidArgumentHelper;
use PHPUnit\Xpath\Constraint\Xpath;
use PHPUnit\Xpath\Constraint\XpathCount;
use PHPUnit\Xpath\Constraint\XpathEquals;
use PHPUnit\Xpath\Constraint\XpathMatch;
use DOMNode;
use stdClass;
use JsonSerializable;
use PHPUnit\Framework\Exception as PHPUnitException;
use ArrayAccess;

/**
 * Trait that with Xpath based assertions
 */
trait Assert
{
    /**
     * Asserts that DOM node matches a specified Xpath expression.
     *
     * @throws PHPUnitException
     */
    public static function assertXpathMatch(
        string $expression,
        DOMNode|array|stdClass|JsonSerializable $context,
        array|ArrayAccess $namespaces = [],
        string $message = ''
    ): void {
        Xpath::isValidContext($context, 2);

        $constraint = new XpathMatch($expression, $namespaces);
        static::assertThat($context, $constraint, $message);
    }

    /**
     * Asserts that DOM node matches a specified Xpath expression.
     *
     * @throws PHPUnitException
     */
    public static function assertXpathCount(
        int|string $expected,
        string $expression,
        DOMNode|array|stdClass|JsonSerializable $context,
        array|ArrayAccess $namespaces = [],
        string $message = ''
    ): void {
        if (!(\is_int($expected) || \is_string($expected))) {
            throw InvalidArgumentHelper::factory(
                1,
                'integer or string'
            );
        }

        Xpath::isValidContext($context, 3);

        $constraint = new XpathCount($expected, $expression, $namespaces);
        static::assertThat($context, $constraint, $message);
    }

    /**
     * Asserts that DOM nodes returned by an Xpath expresion are equal to the expected
     *
     * @throws PHPUnitException
     */
    public static function assertXpathEquals(
        mixed $expected,
        string $expression,
        DOMNode|array|stdClass|JsonSerializable $context,
        array|ArrayAccess $namespaces = [],
        string $message = ''
    ): void {
        Xpath::isValidContext($context, 3);

        $constraint = new XpathEquals($expected, $expression, $namespaces);
        static::assertThat($context, $constraint, $message);
    }
}
