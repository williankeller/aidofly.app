<?php

namespace App\Services\Agents\Preset;

use JsonSerializable;
use Override;

class Option implements JsonSerializable
{
    public function __construct(
        public readonly string $value,
        public readonly string $label
    ) {
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label
        ];
    }
}
