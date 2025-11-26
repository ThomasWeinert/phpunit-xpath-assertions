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

use JsonSerializable;
use ReturnTypeWillChange;

class JsonSerializableExample implements JsonSerializable
{
    public function __construct(private mixed $data)
    {
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
