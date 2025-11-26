<?php

declare(strict_types=1);

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
