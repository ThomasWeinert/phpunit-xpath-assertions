<?php

declare(strict_types=1);

/*
 * This file is part of phpunit-xpath-assertions.
 *
 * (c) Thomas Weinert <thomas@weinert.info>
 * (c) Fabian Grutschus <github@lubyte.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Xpath\Constraint;

use DOMNodeList;

use function sprintf;

/**
 * Constraint that asserts that the result of an Xpath
 * expression is TRUE and not empty.
 *
 * The Xpath expression and namespaces are passed in the constructor.
 */
class XpathMatch extends AbstractXpath
{
    /**
     * @param mixed $other Value or object to evaluate.
     */
    protected function matches(mixed $other): bool
    {
        $actual = $this->evaluateXpathAgainst($other);
        if ($actual instanceof DOMNodeList) {
            return $actual->length > 0;
        }

        return (bool) $actual;
    }

    public function toString(): string
    {
        return sprintf(
            'matches expression: %s',
            $this->expression
        );
    }
}
