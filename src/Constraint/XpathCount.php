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

use function sprintf;

/**
 * Constraint that asserts that the result of an Xpath
 * expression is a node list with the specific count of nodes.
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
class XpathCount extends AbstractXpath
{
    private int $actualCount = 0;

    public function __construct(private int $expectedCount, string $expression, array $namespaces = [])
    {
        parent::__construct($expression, $namespaces);
        $this->expectedCount = $expectedCount;
    }

    /**
     * @param mixed $other Value or object to evaluate.
     */
    protected function matches(mixed $other): bool
    {
        $actual = $this->evaluateXpathAgainst($other);

        $this->actualCount = $actual->length;

        return $this->actualCount === $this->expectedCount;
    }

    protected function failureDescription(mixed $other): string
    {
        return sprintf(
            'actual node count %d matches expected count %d',
            $this->actualCount,
            $this->expectedCount
        );
    }

    public function toString(): string
    {
        return sprintf(
            'count matches %d',
            $this->expectedCount
        );
    }
}
