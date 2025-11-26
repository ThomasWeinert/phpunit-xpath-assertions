<?php

declare(strict_types=1);

namespace PHPUnit\Xpath;

use PHPUnit\Xpath\Constraint\Xpath;

class TestProxy extends Xpath
{
    public function proxyEvaluateXpathAgainst(mixed $context)
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
