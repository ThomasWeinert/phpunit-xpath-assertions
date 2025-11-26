<?php

declare(strict_types=1);

namespace PHPUnit\Xpath;

use JsonSerializable;

class JsonSerializableExample implements JsonSerializable
{
    public function __construct(private mixed $data)
    {
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
