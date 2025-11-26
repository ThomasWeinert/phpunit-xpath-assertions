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

namespace PHPUnit\Xpath;

use DOMNodeList;
use PHPUnit\Xpath\Constraint\AbstractXpath;

class TestProxy extends AbstractXpath
{
    public function proxyEvaluateXpathAgainst(mixed $context): DOMNodeList|bool|string|float
    {
        return parent::evaluateXpathAgainst($context);
    }

    public function matches(mixed $other): bool
    {
    }

    public function toString(): string
    {
        return '';
    }
}
