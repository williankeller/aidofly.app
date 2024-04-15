<?php

namespace App\Services\Stream\Domain;

use JsonSerializable;

class Token implements JsonSerializable
{
    public function __construct(
        public readonly string $value
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
